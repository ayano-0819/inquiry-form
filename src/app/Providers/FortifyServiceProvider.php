<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use App\Responses\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            CreatesNewUsers::class,
            CreateNewUser::class
        );

        // ★ ログアウト後のレスポンスを差し替える
        $this->app->singleton(
            LogoutResponseContract::class,
            LogoutResponse::class
    );
    }

    public function boot(): void
    {
        // 新規登録画面
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // ★ログイン：FormRequest(LoginRequest)のルール/メッセージを使ってバリデーション→認証
        Fortify::authenticateUsing(function (Request $request) {

            // FormRequestの rules/messages を使う
            $form = app(LoginRequest::class);

            Validator::make(
                $request->all(),
                $form->rules(),
                $form->messages()
            )->validate();

            // 認証（email+password）
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user; // ログイン成功
            }

            throw ValidationException::withMessages([
                'password' => 'ログイン情報が登録されていません',
            ]);
            
            });

        // ログイン試行回数制限
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
