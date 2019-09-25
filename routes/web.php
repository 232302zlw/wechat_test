<?php

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

use App\Http\Controllers\testone\WechatController;
use App\Http\Controllers\testtwo\SignController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('wechat')->namespace('testone')->group(function(){
    Route::get('login','WechatController@login');                           // 授权登录
    Route::get('code','WechatController@code');                             // 获取信息
    Route::get('create_tag','WechatController@create_tag');                 // 创建标签视图
    Route::post('save_tag','WechatController@save_tag');                    // 创建标签处理
    Route::get('list_tag','WechatController@list_tag');                     // 标签列表
    Route::get('user_list','WechatController@user_list');                   // 用户列表
    Route::post('save_tag_openid','WechatController@save_tag_openid');      // 用户添加标签
    Route::get('send_message','WechatController@send_message');             // 标签发送消息
    Route::post('send_message_do','WechatController@send_message_do');      // 标签发送消息

    Route::get('get_access_token','WechatController@get_access_token');     // 获取token
});


Route::prefix('sign')->namespace('testtwo')->group(function(){
    Route::get('create_menu','SignController@create_menu');
    Route::post('save_menu','SignController@save_menu');
    Route::get('menu','SignController@menu');
});
