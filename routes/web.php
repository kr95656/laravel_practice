<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ItemsController@showItems')->name('top');

Auth::routes();

Route::get('items/{item}', 'ItemsController@showItemsDetails')->name('item');

Route::middleware('auth')
->group(function () {
    Route::get('items/{item}/buy', 'ItemsController@showItemsBuyForm')->name('item.buy');
    Route::post('items/{item}/buy', 'ItemsController@BuyItem')->name('item.buy');


    Route::get('sell', 'SellController@showSoldItems')->name('sell');
    Route::post('sell', 'SellController@selltems')->name('sell');
});

Route::prefix('mypage') //urlの共通部分を指定
    ->namespace('MyPage') //コントローラーの接頭辞を指定
    ->middleware('auth')
    ->group(function() {
        Route::get('edit-profile', 'ProfileController@showProfileEditForm')->name('mypage.edit-profile');
        Route::post('edit-profile', 'ProfileController@editProfile')->name('mypage.edit-profile');
        Route::get('sold-items', 'SoldItemsController@showSoldItems')->name('mypage.sold-items');
        Route::get('bought-items', 'BoughtItemsController@showBoughtItems')->name('mypage.bought-items');
    });
