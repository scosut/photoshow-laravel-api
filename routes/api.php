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

Route::middleware(['cors'])->group(function() {
  Route::get('/albums', 'AlbumsController@index');
  Route::get('/albums/create', 'AlbumsController@create');
  Route::post('/albums/store', 'AlbumsController@store');
  Route::get('/albums/{id}', 'AlbumsController@show');
  Route::get('/photos/create/{albumId}', 'PhotosController@create');
  Route::post('/photos/store', 'PhotosController@store');
  Route::get('/photos/{id}', 'PhotosController@show');
  Route::delete('/photos/{id}', 'PhotosController@destroy');
});


