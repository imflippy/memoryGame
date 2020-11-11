<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

Route::group([
  'middleware' => 'api',
  'prefix' => 'auth'
], function ($router) {
  Route::post('login', 'AuthController@login');
  Route::post('register', 'AuthController@register');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::get('user-profile', 'AuthController@userProfile');
  Route::get('/online', 'AuthController@list');

 Route::post('generate-game', 'GameController@generateGame');
});


Route::group([
  'middleware' => 'api',
], function ($router) {

});


Route::post('push', 'SinglePageController@push');

//Route::post('online', 'UserController@online');

//Route::get('/online', 'UpdateController@list');
//Route::post('/online', 'UpdateController@create');

