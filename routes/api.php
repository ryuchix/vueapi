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

// Route::get('getMonster/{id}', 'ItemController@getMonster');
// Route::get('getMonsters', 'ItemController@getMonsters');
// Route::get('saveMonsterItem', 'MonsterController@saveMonsterItem');
// Route::get('saveExtraStat', 'ItemController@getExtraStat');
// Route::get('addSingleItem', 'ItemController@addSingleItem');
// Route::get('getItemSets', 'ItemController@getItemSets');
// Route::get('runNull', 'ItemController@runNull');
// Route::get('getBlueprintMissingitem', 'ItemController@getBlueprintMissingitem');
// Route::get('runMissingKeyId', 'ItemController@runMissingKeyId');
// Route::get('getCompose', 'ItemController@getCompose');
// Route::get('getSynth', 'NpcController@getSynth');
// Route::get('addRegularItem', 'NpcController@addRegularItem');
// Route::get('modifySynth', 'NpcController@modifySynth');
// Route::get('addSlugInItems', 'NpcController@addSlugInItems');
// Route::get('renameSlots', 'NpcController@renameSlots');
// Route::get('getMissingInfoInHeadwears', 'NpcController@getMissingInfoInHeadwears');
// Route::get('getMissingCardImage', 'NpcController@getMissingCardImage');
Route::get('removeAsterisk', 'NpcController@removeAsterisk');
// Route::get('addJobClass', 'NpcController@addJobClass');


Route::get('addFurniture', 'NpcController@addFurniture');

Route::get('getTypeName', 'NpcController@getTypeName');


// Route::post('getHeadwears', 'NpcController@getHeadwears');
// Route::post('getCards', 'NpcController@getCards');
// api

Route::post('saveWeeklies', 'SitemapController@saveWeeklies');
Route::get('getWeeklies', 'SitemapController@getWeeklies');

// sitemaps
Route::get('/sitemap', 'SitemapController@index');
Route::get('/sitemap/monsters', 'SitemapController@monsters');
Route::get('/sitemap/cards', 'SitemapController@cards');
Route::get('/sitemap/equipments', 'SitemapController@equipments');
Route::get('/sitemap/items', 'SitemapController@items');
Route::get('/sitemap/posts', 'SitemapController@blogs');
Route::get('/sitemap/headwears', 'SitemapController@headwears');
Route::get('/sitemap/furnitures', 'SitemapController@furnitures');

Route::get('monsters', 'MonsterController@index');
Route::get('monster/{id}', 'MonsterController@getMonster');
Route::get('filter-monsters', 'MonsterController@filterMonster');

Route::get('equipments', 'ItemController@equipments');
Route::get('equipment/{id}', 'ItemController@getEquipment');
Route::get('filter-equipments', 'ItemController@filterEquipment');

Route::get('cards', 'ItemController@cards');
Route::get('card/{id}', 'ItemController@getCard');
Route::get('filter-cards', 'ItemController@filterCard');

Route::get('items', 'ItemController@getItems');
Route::get('item/{id}', 'ItemController@getItem');

Route::get('headwears', 'ItemController@getHeadwears');
Route::get('headwear/{id}', 'ItemController@getHeadwear');
Route::get('filter-headwears', 'ItemController@filterHeadwear');

Route::get('furnitures', 'ItemController@getFurnitures');
Route::get('furniture/{id}', 'ItemController@getFurniture');
Route::get('filter-furnitures', 'ItemController@filterFurniture');

Route::get('blogs', 'BlogController@index');
Route::get('blog/{slug}', 'BlogController@getBlog');

Route::get('search/{query}', 'ItemController@search');
