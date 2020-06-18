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

Route::get('getMonster/{id}', 'ItemController@getMonster');
Route::get('getMonsters', 'ItemController@getMonsters');

// api
Route::get('monsters', 'MonsterController@index');
Route::get('monster/{id}', 'MonsterController@getMonster');
