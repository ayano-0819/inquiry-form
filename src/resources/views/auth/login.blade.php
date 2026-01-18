@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

{{-- 右上の register ボタン（この画面だけ） --}}
<a href="/register" class="login-switch">register</a>

<div class="login">
    <h2 class="login__title">Login</h2>

    <div class="login__card">
        <form action="/login" method="post" class="login__form">
            @csrf

            <div class="login__group">
                <label class="login__label">メールアドレス</label>
                <input
                    type="email"
                    name="email"
                    class="login__input"
                    placeholder="例: test@example.com"
                    value="{{ old('email') }}"
                >

                @error('email')
                <div class="login-form__error">{{ $message }}</div>
                @enderror
            </div>

            <div class="login__group">
                <label class="login__label">パスワード</label>
                <input
                    type="password"
                    name="password"
                    class="login__input"
                    placeholder="例: coachtech1106"
                >

                @error('password')
                <div class="login-form__error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="login__button">
                ログイン
            </button>
        </form>
    </div>
</div>
@endsection
