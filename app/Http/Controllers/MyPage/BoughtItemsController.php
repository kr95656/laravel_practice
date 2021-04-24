<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoughtItemsController extends Controller
{
    public function showBoughtItems()
    {
        // 購入した商品のデータを取得
        /**Item table
        item-image
        primary_category
        secodary_category
        price
        name
        bought_created_time
        **/
        $user = Auth::user();
        $items = $user->boughtitems()
            ->orderBy('id', 'DESC')
            ->get();
        return view('mypage.bought_items')
            ->with('items', $items);
    }
}
