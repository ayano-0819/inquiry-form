@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Tinos:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="confirm">
    <div class="confirm__inner">

        <!-- タイトル -->
        <h1 class="confirm__title">Confirm</h1>

        <!-- 確認テーブル -->
        <form class="contact-form" action="/contacts" method="post">
            @csrf

            <table class="confirm-table">
                <tr>
                    <th>お名前</th>
                    <td>{{ $contact['last_name'] }}　{{ $contact['first_name'] }}</td>
                </tr>

                <tr>
                    <th>性別</th>
                    <td>
                        @if ($contact['gender'] == '1')
                            男性
                        @elseif ($contact['gender'] == '2')
                            女性
                        @else
                            その他
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>メールアドレス</th>
                    <td>{{ $contact['email'] }}</td>
                </tr>

                <tr>
                    <th>電話番号</th>
                    <td>{{ str_replace('-', '', $contact['tel']) }}</td>
                </tr>

                <tr>
                    <th>住所</th>
                    <td>{{ $contact['address'] }}</td>
                </tr>

                <tr>
                    <th>建物名</th>
                    <td>{{ $contact['building'] }}</td>
                </tr>

                <tr>
                    <th>お問い合わせの種類</th>
                    <td>{{ $category->content }}</td>
                </tr>

                <tr>
                    <th>お問い合わせ内容</th>
                    <td class="confirm-table__detail">
                        {{ trim($contact['detail']) }}
                    </td>
                </tr>
            </table>

            <!-- hidden（送信用） -->
            @foreach ($contact as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <!-- ボタン -->
            <div class="confirm__buttons">
                <button type="submit" class="confirm__button-submit">送信</button>

                <button type="submit" name="back" value="back" formaction="/confirm" formmethod="post" class="confirm__button-back">
                    修正
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
