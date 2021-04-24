<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mypage\Profile\EditRequest;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{
    public function showProfileEditForm()
    {
        return view('mypage.profile_edit_form')
        ->with('user', Auth::user());
    }

    public function editProfile(EditRequest $request)
    {
        $user = Auth::user();

        $user->name = $request->input('name');

        if ($request->has('avatar')) {
            $fileName = $this->saveAvatar($request->file('avatar'));
            $user->avatar_file_name = $fileName;
        }

        $user->save();

        // 直前のページにリダイレクト
        return redirect()->back()->with('status', 'プロフィールを編集しました');
    }

    /**
    * アバター画像をリサイズして保存
    *
    * @param UploadedFile $file アップロードされたアバター画像
    * @return string ファイル名
    */

     private function saveAvatar(UploadedFile $file): string
     {
         $tempPath = $this->makeTempPath();

        /**
         * http://image.intervention.io/use/uploads
         * // read image from temporary file
         * $img = Image::make($_FILES['image']['tmp_name']);
         * // resize image
         * $img->fit(300, 200);
         * // save image
         * $img->save('foo/bar.jpg');
         *
         */
         Image::make($file)->fit(200, 200)->save($tempPath);


        //  https://readouble.com/laravel/5.6/ja/filesystem.html
        //  localドライバを使う場合、設定ファイルで指定したrootディレクトリからの相対位置で全ファイル操作が行われることに注意してください。デフォルトでこの値はstorage/appディレクトリに設定されています。そのため次のメソッドでファイルはstorage/app/file.txtとして保存されます。
        // Storage::disk('local')->put('file.txt', 'Contents');

        //指定したファイル位置のファイルのストリーミングを自動的にLaravelに管理させたい場合は、putFileかputFileAsメソッドを使います。このメソッドは、Illuminate\Http\FileかIlluminate\Http\UploadedFileのインスタンスを引数に取り、希望する場所へファイルを自動的にストリームします。

        // // 自動的に一意のIDがファイル名として指定される
        // Storage::putFile('photos', new File('/path/to/photo'));

        // // ファイル名を指定する
        // Storage::putFileAs('photos', new File('/path/to/photo'), 'photo.jpg');

        // Storage::disk('public') → storage/app/publicをデフォルトのディレクトリに設定
        // putFile('dir', $file) → $fileを'dir'に保存
        // put('file_name', $file) → $fileを'file_name'という名前で保存
         $filePath = Storage::disk('public')->putFile('avatars', new File($tempPath));

        // パスの最後にある名前の部分を返す
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
        // metaデータ取得
        $meta = stream_get_meta_data($tmp_fp);
        return $meta["uri"];
    }


}
