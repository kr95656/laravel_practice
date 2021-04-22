<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ItemsController extends Controller
{

    private function settlement($itemID, $sellerID, $buyerID, $token)
    {
        // トランザクション処理
        DB::beginTransaction();

        try {
            // 排他ロックし、同じレコードに対する処理が並列に実行されるのを防ぐ
            $item = Item::lockForUpdate()->find($itemID);
            $seller = User::lockForUpdate()->find($sellerID);

            if ($item->isStateBought) {
                throw new \Exception('多重決済');
            }

            $item->state = Item::STATE_BOUGHT;
            $item->bought_at = Carbon::now();
            $item->buyer_id = $buyerID;
            $item->save();

            $seller->sales += $item->price;
            $seller->save();

        } catch (\Exception $e) {
            // ロールバック
            DB::rollBack();
            throw $e;
        }

        // DB保存処理確定
        DB::commit();

    }

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

    public function showItemsBuyForm(Item $item)
    {
        if (!$item->isStateSelling) {
            abort(404);
        }
// echo "<pre>";
// var_dump($item);
// echo "</pre>";
        return view('items.item_buy_form')
                ->with('item', $item);
    }


    public function BuyItem(Request $request, Item $item) {
        $user = Auth::user();

        if (!$item->isStateSelling) {
            abort(404);
        }

        $token = $request->input('card-token');

        try {
            $this->settlement($item->id, $item->seller->id, $user->id, $token);
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()
                ->with('type', 'danger')
                ->with('message', '購入処理が失敗しました');
        }

        return redirect()->route('item', [$item->id])
            ->with('message', '商品を購入しました');
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
