@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register">
    <a href="/login" class="register__login-btn">login</a>

    <h2 class="register__title">Register</h2>

    <div class="register__card">
        <form action="/register" method="post" class="register__form">
            @csrf

            <div class="register__group">
                <label class="register__label">お名前</label>
                <input
                    type="text"
                    name="name"
                    class="register__input"
                    placeholder="例：山田 太郎"
                    value="{{ old('name') }}"
                >

                @error('name')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>

            <div class="register__group">
                <label class="register__label">メールアドレス</label>
                <input
                    type="email"
                    name="email"
                    class="register__input"
                    placeholder="例：test@example.com"
                    value="{{ old('email') }}"
                >

                @error('email')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>

            <div class="register__group">
                <label class="register__label">パスワード</label>
                <input
                    type="password"
                    name="password"
                    class="register__input"
                    placeholder="例：coachtech1106"
                >

                @error('password')
                <div class="register-form__error">{{ $message }}</div>
                @enderror
            </div>
    
            <button type="submit" class="register__button">
                登録
            </button>
        </form>
    </div>
</div>
@endsection
