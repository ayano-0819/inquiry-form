<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
     // ログイン画面表示
    public function create()
    {
        return view('auth.login');
    }

    // バリデーション処理
    public function store(LoginRequest $request)
    {
        // ここに来た時点でバリデーションは通っている
        $validated = $request->validated();

        // 今はまだ認証しなくてOK
        // dd($validated);

        return redirect('/'); // 仮でOK
    }
}
