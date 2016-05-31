<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('bulk/clips', 'BulkImportController@importGamePlayClips');
Route::get('yt','YoutubeController@index');
Route::get('home', 'HomeController@showHome');
Route::get('user/{id}', 'UserController@showProfile');
Route::get('bulk/games', 'BulkImportController@importGames');

Route::get('api/loadGamer/{steamId}', 'ApiController@loadGamer');
Route::get('api/loadGamerGames/{userId}', 'ApiController@loadGamerGames');
Route::get('api/rateGamerGame/{gamerId}/{gamerGameId}/{rating}', 'ApiController@rateGamerGame');
Route::get('api/loadGamesToBrowse/{gamerId}', 'ApiController@loadGamesToBrowse');