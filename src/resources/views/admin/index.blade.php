@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin">

    {{-- 右上 logout --}}
    <div class="admin__logout-wrap">
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="admin__logout">logout</button>
    </form>
    </div>

    <h2 class="admin__title">Admin</h2>

    {{-- 検索フォーム --}}
    <form method="GET" action="/search" class="admin__search">
    <input
        type="text"
        name="keyword"
        value="{{ request('keyword') }}"
        class="admin__search-input"
        placeholder="名前やメールアドレスを入力してください"
    >

    <div class="admin__select-wrap">
        <select name="gender" class="admin__select--gender">
        <option value="" {{ request('gender')==='' ? 'selected' : '' }}>性別</option>
        <option value="" {{ request('gender')==='' ? 'selected' : '' }}>全て</option>
        <option value="1" {{ request('gender')=='1' ? 'selected' : '' }}>男性</option>
        <option value="2" {{ request('gender')=='2' ? 'selected' : '' }}>女性</option>
        <option value="3" {{ request('gender')=='3' ? 'selected' : '' }}>その他</option>
        </select>
    </div>

    <div class="admin__select-wrap admin__select-wrap--wide">
        <select name="category_id" class="admin__select--category">
        <option value="">お問い合わせの種類</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id')==$category->id ? 'selected' : '' }}>
            {{ $category->content }}
            </option>
        @endforeach
        </select>
    </div>

    <div class="admin__select-wrap">
        <input type="date" name="date" value="{{ request('date') }}" class="admin__date">
    </div>

    <button type="submit" class="admin__btn admin__btn--dark">検索</button>
    <a href="/reset" class="admin__btn admin__btn--light">リセット</a>
    </form>

    {{-- エクスポート --}}
    <div class="admin__actions">
    <button type="button" class="admin__export">エクスポート</button>

    <div class="admin__pager-top">
        @if ($contacts->hasPages())
    <nav class="admin__pager" aria-label="Pagination">
    {{-- 前へ --}}
    @if ($contacts->onFirstPage())
        <span class="admin__pager-btn admin__pager-btn--disabled">＜</span>
    @else
        <a class="admin__pager-btn" href="{{ $contacts->previousPageUrl() }}">＜</a>
    @endif

    {{-- ページ番号 --}}
    @foreach ($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
        @if ($page == $contacts->currentPage())
        <span class="admin__pager-page admin__pager-page--active">{{ $page }}</span>
        @else
        <a class="admin__pager-page" href="{{ $url }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- 次へ --}}
    @if ($contacts->hasMorePages())
        <a class="admin__pager-btn" href="{{ $contacts->nextPageUrl() }}">＞</a>
    @else
        <span class="admin__pager-btn admin__pager-btn--disabled">＞</span>
    @endif
    </nav>
@endif

    </div>
    </div>

    {{-- 一覧 --}}
<div class="admin__table-wrap">
    <table class="admin__table">
        <thead>
        <tr>
            <th class="admin__th">お名前</th>
            <th class="admin__th">性別</th>
            <th class="admin__th">メールアドレス</th>
            <th class="admin__th">お問い合わせの種類</th>
            <th class="admin__th admin__th--btn"></th>
        </tr>
        </thead>

        <tbody>
            @foreach ($contacts as $contact)
                <tr class="admin__tr">
                    <td class="admin__td">{{ $contact->last_name }} {{ $contact->first_name }}</td>
                    <td class="admin__td">
                        @if($contact->gender == 1) 男性
                        @elseif($contact->gender == 2) 女性
                        @else その他
                        @endif
                    </td>
                    <td class="admin__td">{{ $contact->email }}</td>
                    <td class="admin__td">{{ $contact->category->content ?? '' }}</td>
                    <td class="admin__td admin__td--btn">
                        <button type="button" class="admin__detail" onclick="document.getElementById('modal-{{ $contact->id }}').showModal()">
                        詳細
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>

        @foreach ($contacts as $contact)
            <dialog class="modal" id="modal-{{ $contact->id }}">
                <form method="dialog" class="modal__overlay"></form>

                <div class="modal__panel">
                    <button type="button" class="modal__close-wrap" aria-label="close"
                    onclick="document.getElementById('modal-{{ $contact->id }}').close()">
                    <span class="modal__close">×</span>
                    </button>

                {{-- ★リスト本体 --}}
                <div class="modal__body">
                    <div class="modal__list">
                        <div class="modal__row">
                            <div class="modal__label">お名前</div>
                            <div class="modal__value">{{ $contact->last_name }} {{ $contact->first_name }}</div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">性別</div>
                            <div class="modal__value">
                                @if($contact->gender == 1) 男性
                                @elseif($contact->gender == 2) 女性
                                @else その他
                                @endif
                            </div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">メールアドレス</div>
                            <div class="modal__value">{{ $contact->email }}</div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">電話番号</div>
                            <div class="modal__value">{{ $contact->tel }}</div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">住所</div>
                            <div class="modal__value">
                            {{
                                preg_replace(
                                '/^\s*〒?\s*[0-9０-９]{3}\s*[-‐-‒–—―ー－]?\s*[0-9０-９]{4}\s*/u',
                                '',
                                $contact->address
                                )
                            }}
                            </div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">建物名</div>
                            <div class="modal__value">{{ $contact->building }}</div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">お問い合わせの種類</div>
                            <div class="modal__value">{{ $contact->category->content ?? '' }}</div>
                        </div>

                        <div class="modal__row">
                            <div class="modal__label">お問い合わせ内容</div>
                            <div class="modal__value modal__detail">{{ $contact->detail }}</div>
                        </div>

                    </div>
                </div>

                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="post" class="modal__delete-wrap">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal__delete">削除</button>
                </form>
            </div>
            </dialog>
        @endforeach
    </table>
</div>
@endsection