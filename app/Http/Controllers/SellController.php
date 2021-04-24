<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCondition;
use App\Models\PrimaryCategory;
use App\Http\Requests\SellRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 画像保存処理
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    public function selltems(SellRequest $request)
    {
        $user = Auth::user();

        // input()ではなくfile()を使用
        // file名を指定
        $imageName = $this->saveImage($request->file('item-image'));

        $item = new Item();
        $item->image_file_name = $imageName;
        $item->seller_id = $user->id;
        $item->name = $request->input('name');
        $item->description = $request->input('description');
        $item->secondary_category_id = $request->input('category');
        $item->item_condition_id = $request->input('condition');
        $item->price = $request->input('price');
        $item->state = Item::STATE_SELLING;
        $item->save();

        return redirect()
                ->back()
                ->with('status', '商品を出品しました');
    }

    /**
      * 商品画像をリサイズして保存します
      *
      * @param UploadedFile $file アップロードされた商品画像
      * @return string ファイル名
      */
    private function saveImage(UploadedFile $file): string
    {
        // 一時ファイルを生成してファイルパスを取得
        $tempPath =$this->makeTempPath();

        // Intervention Imageを使用して、
        Image::make($file)->fit(300, 300)->save($tempPath);

        $filePath = Storage::disk('public')->putFile('item-images', new File($tempPath));
        return basename($filePath);
    }

      /**
      * 一時的なファイルを生成してパスを返します。
      *
      * @return string ファイルパス
      */
    private function makeTempPath():string
    {
        $tmp_fp = tmpfile();
        $meta = stream_get_meta_data($tmp_fp);
        return $meta['uri'];
    }

}
