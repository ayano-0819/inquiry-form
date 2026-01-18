@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}" />
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Tinos:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div class="contact-form__content">
        <h1 class="contact-form__title">Contact</h1>

        <div class="contact-form__inner">
    {{-- フォーム --}}
            <form class="contact-form" action="/confirm" method="post">
                @csrf

    {{-- お名前 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                        お名前 <span class="contact-form__required">※</span>
                    </label>

                    <div class="contact-form__control">
                        <div class="contact-form__inputs">
                            <div class="contact-form__input-wrap">
                                <input type="text" name="last_name" placeholder="例：山田" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="contact-form__error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="contact-form__input-wrap">
                                <input type="text" name="first_name" placeholder="例：太郎" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="contact-form__error">{{ $message }}</div>
                                @enderror
                            </div>   
                        </div> 
                    </div>
                </div>
    {{-- 性別 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                        性別 <span class="contact-form__required">※</span>
                    </label>
                    <div class="contact-form__field">
                        <div class="contact-form__radios">
                            <label><input type="radio" name="gender" value="1" {{ old('gender') == '1' ? 'checked' : '' }}> 男性</label>
                            <label><input type="radio" name="gender" value="2" {{ old('gender') == '2' ? 'checked' : '' }}> 女性</label>
                            <label><input type="radio" name="gender" value="3" {{ old('gender') == '3' ? 'checked' : '' }}> その他</label>
                        </div>
                        <div class="contact-form__error">
                            @error('gender')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

    {{-- メールアドレス --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                        メールアドレス <span class="contact-form__required">※</span>
                    </label>

                    <div class="contact-form__control">
                        <input type="email" name="email" placeholder="例：test@example.com" value="{{ old('email') }}">

                        <div class="contact-form__error">
                        @error('email')
                        {{ $message }}
                        @enderror
                        </div>
                    </div>
                </div>

    {{-- 電話番号 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                        電話番号 <span class="contact-form__required">※</span>
                    </label>

                    <div class="contact-form__control">
                        <div class="contact-form__inputs">
                            <input type="text" name="tel1" placeholder="080" value="{{ old('tel1') }}">
                            <span>-</span>
                            <input type="text" name="tel2" placeholder="1234" value="{{ old('tel2') }}">
                            <span>-</span>
                            <input type="text" name="tel3" placeholder="5678" value="{{ old('tel3') }}">
                        </div>

                        @if ($errors->hasAny(['tel1', 'tel2', 'tel3']))
                            <p class="contact-form__error">
                                {{ $errors->first('tel1') 
                                    ?? $errors->first('tel2') 
                                    ?? $errors->first('tel3') }}
                            </p>
                        @endif
                    </div>
                </div>

    {{-- 住所 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                        住所 <span class="contact-form__required">※</span>
                    </label>

                    <div class="contact-form__control">
                        <input type="text" name="address" placeholder="例：東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}">
                        
                        <div class="contact-form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                        </div>
                    </div>
                </div>

    {{-- 建物 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">建物名</label>

                    <div class="contact-form__control">
                        <input type="text" name="building" placeholder="例：千駄ヶ谷マンション101" value="{{ old('building') }}">
                    </div>   
                </div>

    {{-- お問い合わせの種類 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                    お問い合わせの種類 <span class="contact-form__required">※</span>
                    </label>

                    <div class="contact-form__control">
                        <div class="contact-form__select-wrap">
                            <select name="categry_id" class="contact-form__select">
                                <option value="" disabled {{ old('categry_id') ? '' : 'selected' }}>
                                    選択してください
                                </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categry_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->content }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="contact-form__error">
                            @error('categry_id')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

    {{-- お問い合わせ内容 --}}
                <div class="contact-form__group">
                    <label class="contact-form__label">
                        お問い合わせ内容 <span class="contact-form__required">※</span>
                    </label>

                    <div class="contact-form__control">
                        <textarea name="detail" rows="5" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
                        <div class="contact-form__error">
                            @error('detail')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

    {{-- ボタン --}}
                <div class="contact-form__button">
                    <button type="submit">確認画面</button>
                </div>
            </form>
        </div>
    </div>
@endsection