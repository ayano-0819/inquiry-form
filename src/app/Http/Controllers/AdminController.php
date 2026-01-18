<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // カテゴリ一覧（プルダウン用）
        $categories = Category::all();

        // ① まずQueryを作る（ここに検索条件を追加していく）
        $query = Contact::with('category')->latest();

        // ② キーワード（名前：姓/名/フルネーム、メール：部分一致）
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('last_name', 'like', "%{$keyword}%")
                    ->orWhere('first_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    // フルネーム（スペース無し/有り）
                    ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$keyword}%"])
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$keyword}%"]);
            });
        }

        // ③ 性別（空＝全て）
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // ④ お問い合わせの種類（contacts側は categry_id なので注意）
        if ($request->filled('category_id')) {
            $query->where('categry_id', $request->category_id);
        }

        // ⑤ 日付（created_at の日付一致）
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // ⑥ 7件ずつ＋ページ移動しても検索条件を保持
        $contacts = $query
            ->paginate(7)
            ->appends($request->query());

        return view('admin.index', compact('contacts', 'categories'));
    }

    public function reset()
    {
        // 検索条件をすべてクリアして管理画面へ
        return redirect('/admin');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect('/admin');
    }

    public function export(Request $request)
{
    // indexと同じ：categoryも一緒に読み込む（CSVにカテゴリ名を入れたい場合に便利）
    $query = Contact::with('category')->latest();

    // ② キーワード（indexと同じ）
    if ($request->filled('keyword')) {
        $keyword = trim($request->keyword);

        $query->where(function ($q) use ($keyword) {
            $q->where('last_name', 'like', "%{$keyword}%")
              ->orWhere('first_name', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%")
              ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$keyword}%"])
              ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$keyword}%"]);
        });
    }

    // ③ 性別（indexと同じ）
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }

    // ④ お問い合わせの種類（indexと同じ：categry_id 注意）
    if ($request->filled('category_id')) {
        $query->where('categry_id', $request->category_id);
    }

    // ⑤ 日付（indexと同じ）
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // ✅ CSVは「表示中の一覧＝検索結果」を全部出す（ページング無し）
    $contacts = $query->get();

    $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

    return response()->streamDownload(function () use ($contacts) {
        $out = fopen('php://output', 'w');

        // ヘッダー
        fputcsv($out, $this->toSjis([
            'ID', 'お名前', '性別', 'メール', 'カテゴリ', '住所', '作成日'
        ]));

        foreach ($contacts as $c) {
            // 性別表示（要件に合わせて数字のままでもOK）
            $genderText = match ((int)$c->gender) {
                1 => '男性',
                2 => '女性',
                default => '不明',
            };

            $row = [
                $c->id,
                $c->last_name . ' ' . $c->first_name,
                $genderText,
                $c->email,
                optional($c->category)->content, // categoriesテーブルのcontent
                $c->address,
                optional($c->created_at)->format('Y-m-d H:i:s'),
            ];

            fputcsv($out, $this->toSjis($row));
        }

        fclose($out);
    }, $filename, [
        'Content-Type' => 'text/csv; charset=Shift_JIS',
    ]);
}

private function toSjis(array $values): array
{
    return array_map(fn ($v) => mb_convert_encoding((string)$v, 'SJIS-win', 'UTF-8'), $values);
}

}