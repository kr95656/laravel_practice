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
        $categories = PrimaryCategory::orderBy('sort_no')->get();
        $conditons = ItemCondition::orderBy('sort_no')->get();
        return view('sell')
                ->with('conditions', $conditons)
                ->with('categories', $categories);
    }
}
