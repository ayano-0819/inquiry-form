<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Models\Category;
use App\Models\Contact;

class InquiryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(InquiryRequest $request)
    {
    
     // 「修正」ボタンが押された場合：入力画面へ戻す（入力保持）
    if ($request->has('back')) {
        return redirect()->route('contact.index')->withInput();
    }

    $contact = $request->only([
        'last_name',
        'first_name',
        'gender',
        'email',
        'tel1',
        'tel2',
        'tel3',
        'address',
        'building',
        'categry_id',
        'detail'
    ]);

    // 電話番号を結合
    $contact['tel'] =$contact['tel1'] . '-' .$contact['tel2'] . '-' .$contact['tel3'];

    // カテゴリー取得
    $category = Category::find($contact['categry_id']);

    return view('confirm', compact('contact', 'category'));
    }

    public function store(InquiryRequest $request)
    {
        $contact = $request->only([
        'last_name',
        'first_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'categry_id',
        'detail'
    ]);
        Contact::create($contact);
        return redirect('/thanks');
    }

    public function thanks()
    {
        return view('thanks');
    }
}
