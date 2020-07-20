<?php

use Illuminate\Support\Facades\Route;
use App\Event;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/headwear', function () {
    return view('headwear')->with(['item' => '']);
});

Route::get('/card', function () {
    return view('card')->with(['item' => '']);
});

Route::get('/weekly', function () {
    $event = Event::find(1);
    return view('weekly', compact('event'))->with(['item' => '']);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
