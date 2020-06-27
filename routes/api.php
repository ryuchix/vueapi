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
Route::get('saveMonsterItem', 'MonsterController@saveMonsterItem');
Route::get('saveExtraStat', 'ItemController@getExtraStat');
Route::get('addSingleItem', 'ItemController@addSingleItem');
Route::get('getItemSets', 'ItemController@getItemSets');
Route::get('runNull', 'ItemController@runNull');
Route::get('getBlueprintMissingitem', 'ItemController@getBlueprintMissingitem');
Route::get('runMissingKeyId', 'ItemController@runMissingKeyId');

// api
Route::get('monsters', 'MonsterController@index');
Route::get('monster/{id}', 'MonsterController@getMonster');

Route::get('equipments', 'ItemController@equipments');
Route::get('equipment/{id}', 'ItemController@getEquipment');

Route::get('cards', 'ItemController@cards');
Route::get('card/{id}', 'ItemController@getCard');

Route::get('items', 'ItemController@getItems');
Route::get('item/{id}', 'ItemController@getItem');

Route::get('headwears', 'ItemController@getHeadwears');
Route::get('headwear/{id}', 'ItemController@getHeadwear');


