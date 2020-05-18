<?php

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

Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::middleware('auth:sanctum')->group(function() {
    Route::put('/user', 'UserController@update');
    Route::get('/user/search/{search?}', 'UserController@search');
    Route::get('/user/{user?}', 'UserController@show');
    Route::get('/user/{user}/followers', 'UserController@followers');
    Route::get('/user/{user}/followings', 'UserController@followings');
    Route::post('/user/{user}/toogleFollow', 'UserController@toogleFollow');

    Route::get('/buzz/feed', 'BuzzController@feed');
    Route::get('/buzz/{user}', 'BuzzController@buzzsByUser');
});
