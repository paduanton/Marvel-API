<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('character/{name}', 'API\MarvelController@getCharacterId');
Route::get('character/id/{id_character}', 'API\MarvelController@getCharacteryById');
Route::get('character/stories/{id_character}', 'API\MarvelController@getStoriesByCharacterId');
Route::get('character/comics/{id_story}', 'API\MarvelController@getComicsByStoryId');
