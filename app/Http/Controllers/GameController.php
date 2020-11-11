<?php

namespace App\Http\Controllers;

use App\Events\GenerateGameEvent;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function generateGame(Request $request) {
      $players = $request->input('players');
      $roomId = rand();

      $roomPlayersKeys = array_rand($players, 2);
      $roomPlayersIds = [];

      foreach ($roomPlayersKeys as $key) {
        $roomPlayersIds[] = $players[$key]['id'];
      }
      broadcast(new GenerateGameEvent($roomPlayersIds, $roomId));
    }
}
