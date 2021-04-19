<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemsController extends Controller
{
    public function showItems(Request $request)
    {
        $query = Item::query();
        // カテゴリで絞り込み
        // filledメソッド -> パラメータが指定されているかを調べて真偽値を返す
        if ($request->filled('category')) {
            list($categoryType, $categoryID) = explode(':', $request->input('category'));

            if ($categoryType === 'primary') {
                $query->whereHas('secondaryCategory', function ($query) use ($categoryID){
                    $query->where('primary_category_id', $categoryID);
                });
            } elseif ($categoryType === 'secondary') {
                $query->where('secondary_category_id', $categoryID);
            }
        }

        // キーワードで絞り込み
        if ($request->filled('keyword')) {
            $keyword = '%' . $this->escape($request->input('keyword')) . '%';
            $query->where(function ($query) use ($keyword){
                $query->where('name', 'LIKE', $keyword);
                $query->orWhere('description', 'LIKE', $keyword);
            });
        }

        // ORDER BY句のSQLを直接記述
        // ORDER BY FIELD(state, 'selling', 'bought')
        $items = $query->orderByRaw( "FIELD(state, '" . Item::STATE_SELLING . "', '" . Item::STATE_BOUGHT . "')" )
            ->orderBy('id', 'DESC')
            // ->get();
            // ->paginate(1);
            ->paginate(52);

        return view('items.items')
                ->with('items', $items);
    }

    public function showItemsDetails (Item $item) //routeのルートパラメータで指定した変数を指定
    {
        return view('items.item_details')
            ->with('item', $item);
    }

    public function escape(string $value)
    {
        return str_replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            $value
        );
    }
}
