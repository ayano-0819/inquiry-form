@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thanks">
    <div class="thanks__inner">

        <!-- 背景文字 -->
        <p class="thanks__bg-text">Thank you</p>

        <!-- メッセージ -->
        <p class="thanks__message">
            お問い合わせありがとうございました
        </p>

        <!-- HOMEボタン -->
        <a href="{{ route('contact.index') }}" class="thanks__button">
            HOME
        </a>

    </div>
</div>
@endsection
