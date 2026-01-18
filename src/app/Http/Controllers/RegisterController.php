<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        // ここに来た時点でバリデーションは通っている

        $validated = $request->validated();

        // 今は確認用でOK
        // dd($validated);

        return redirect('/login');
    }
}
