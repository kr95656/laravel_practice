<?php

namespace App\Http\Controllers;

use App\Models\ItemCondition;
use App\Models\PrimaryCategory;
use Illuminate\Http\Request;


class SellController extends Controller
{
    public function showSoldItems()
    {
        // クエリを発行
        $conditons = ItemCondition::orderBy('sort_no')->get();
        // N+1解消
        $categories = PrimaryCategory::query()
                        ->with([
                            //Eloquent Modelでリレーションを定義しているメソッド名
                            'secondaryCategories' => function ($query) {
                                $query->orderBy('sort_no');
                            }
                        ])
                        ->orderBy('sort_no')
                        ->get();

        return view('sell')
                ->with('conditions', $conditons)
                ->with('categories', $categories);
    }
}
