<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['custom_api_auth'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('feed', ['as' => 'feed', 'uses' => 'App\Http\Controllers\ArticalController@feed'])->name('news-feed');
    Route::get('sources', ['as' => 'feed', 'uses' => 'App\Http\Controllers\ArticalController@sources'])->name('news-sources');
});

Route::post('login', ['as' => 'login', 'uses' => 'App\Http\Controllers\LogistrationController@login']);
Route::post('register', ['as' => 'register', 'uses' => 'App\Http\Controllers\LogistrationController@signup']);
