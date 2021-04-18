<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemsController extends Controller
{
    public function showItems(Request $request)
    {
        // ORDER BY句のSQLを直接記述
        // ORDER BY FIELD(state, 'selling', 'bought')
        $items = Item::orderByRaw( "FIELD(state, '" . Item::STATE_SELLING . "', '" . Item::STATE_BOUGHT . "')" )
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
}
