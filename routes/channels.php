<?php

use Illuminate\Support\Facades\Broadcast;


/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});
//Broadcast::channel('updates', function ($user) {
//  return auth()->check();
//});




Broadcast::channel('lobby', function ($user) {
    if(auth()->check()) {
      return $user;
    }
});

Broadcast::channel('game.{gameId}', function ($game, $gameId) {
  if(auth()->check()) {
    return $game;
  }
});


Broadcast::channel('game-info-users.{gameId}', function ($user, $gameId) {
  if(auth()->check()) {
    return $user;
  }
});
