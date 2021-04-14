<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // runメソッド
        // Seederを実行するとrunメソッドが実行
        // DBにデータを登録する処理を記述

        // DBにデータを追加方法
        // Factoryを使用してデータを登録
        // まずはFactoryのインスタンスを取得

        // 第一引数にデータを登録したいEloquentModelのクラスを指定
        // factory(User::class) Userクラス(つまりusersテーブル)に対応するFactoryを取得
        // 次にcreateメソッドを呼び出してレコードを登録
        factory(User::class)->create([
            'name' => 'testuser',
            'email' => 'test@test.jp',
            'email_verified_at' => now(),
            'password' => Hash::make('testtest'),
        ]);
    }
}
