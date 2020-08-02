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


// Route::get('addFurniture', 'NpcController@addFurniture');

// Route::get('getTypeName', 'NpcController@getTypeName');


// Route::post('getHeadwears', 'NpcController@getHeadwears');
// Route::post('getCards', 'NpcController@getCards');
// api

Route::post('saveWeeklies', 'SitemapController@saveWeeklies')->middleware('cors');
Route::get('getWeeklies', 'SitemapController@getWeeklies')->middleware('cors');

// sitemaps
Route::get('/sitemap', 'SitemapController@index')->middleware('cors');
Route::get('/sitemap/monsters', 'SitemapController@monsters')->middleware('cors');
Route::get('/sitemap/cards', 'SitemapController@cards')->middleware('cors');
Route::get('/sitemap/equipments', 'SitemapController@equipments')->middleware('cors');
Route::get('/sitemap/items', 'SitemapController@items')->middleware('cors');
Route::get('/sitemap/posts', 'SitemapController@blogs')->middleware('cors');
Route::get('/sitemap/headwears', 'SitemapController@headwears')->middleware('cors');
Route::get('/sitemap/furnitures', 'SitemapController@furnitures')->middleware('cors');
Route::get('/sitemap/pets', 'SitemapController@pets')->middleware('cors');

Route::get('monsters', 'MonsterController@index')->middleware('cors');
Route::get('monster/{id}', 'MonsterController@getMonster')->middleware('cors');
Route::get('filter-monsters', 'MonsterController@filterMonster')->middleware('cors');

Route::get('equipments', 'ItemController@equipments')->middleware('cors');
Route::get('equipment/{id}', 'ItemController@getEquipment')->middleware('cors');
Route::get('filter-equipments', 'ItemController@filterEquipment')->middleware('cors');

Route::get('cards', 'ItemController@cards')->middleware('cors');
Route::get('card/{id}', 'ItemController@getCard')->middleware('cors');
Route::get('filter-cards', 'ItemController@filterCard')->middleware('cors');

Route::get('items', 'ItemController@getItems')->middleware('cors');
Route::get('item/{id}', 'ItemController@getItem')->middleware('cors');

Route::get('headwears', 'ItemController@getHeadwears')->middleware('cors');
Route::get('headwear/{id}', 'ItemController@getHeadwear')->middleware('cors');
Route::get('filter-headwears', 'ItemController@filterHeadwear')->middleware('cors');

Route::get('furnitures', 'ItemController@getFurnitures')->middleware('cors');
Route::get('furniture/{id}', 'ItemController@getFurniture')->middleware('cors');
Route::get('filter-furnitures', 'ItemController@filterFurniture')->middleware('cors');

Route::get('blogs', 'BlogController@index')->middleware('cors');
Route::get('blog/{slug}', 'BlogController@getBlog')->middleware('cors');

Route::get('search/{query}', 'ItemController@search')->middleware('cors');

Route::get('pets', 'PetController@index')->middleware('cors');
Route::get('pet/{id}', 'PetController@getPet')->middleware('cors');
Route::get('filter-pets', 'PetController@filterPet')->middleware('cors');

Route::get('savePet', 'PetController@savePet');