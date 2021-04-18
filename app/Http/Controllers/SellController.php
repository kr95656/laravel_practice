<?php

namespace App\Http\Controllers;

use App\Models\ItemCondition;
use Illuminate\Http\Request;

// seederを使用するいみとは？

class SellController extends Controller
{
    public function showSoldItems()
    {
        // クエリを発行
        $conditons = ItemCondition::orderBy('sort_no')->get();
        return view('sell')->with('conditions', $conditons);
    }
}
