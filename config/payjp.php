<?php

return [
    // .envまたは環境変数の値を取得
    // env関数を使うのはconfig内のみにする
    // configをキャッシュすると、env関数が必ずnullを返す仕様のため
    'public_key' => env('PAYJP_PUBLIC_KEY'),
    'secret_key' => env('PAYJP_SECRET_KEY')
];
