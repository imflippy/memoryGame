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
  Route::get('ping-user', 'AuthController@pingUser');
  Route::get('/online', 'AuthController@list');

 Route::post('generate-game', 'GameController@generateGame'); //validated
 Route::post('win-game', 'GameController@winGame'); //validated
});


Route::group([
  'middleware' => 'api',
], function ($router) {
  Route::get('get-cards', "CardsController@getCards"); //validated
  Route::post('open-card', "CardsController@openCard"); //validated

  Route::get('lb-data', "GameController@leaderboard"); //nothing to validate

});


//Route::post('push', 'SinglePageController@push');

//Route::post('online', 'UserController@online');

//Route::get('/online', 'UpdateController@list');
//Route::post('/online', 'UpdateController@create');

