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

Route::get('personagem/{nome}', 'API\MarvelController@getPersonagemId');
Route::get('personagem/id/{id_personagem}', 'API\MarvelController@getPersonagem');
Route::get('historia/{id_personagem}', 'API\MarvelController@getHistoria');
Route::get('quadrinho/{id_historia}', 'API\MarvelController@getQuadrinho');
