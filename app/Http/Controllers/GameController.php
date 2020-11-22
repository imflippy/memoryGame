<?php

namespace App\Http\Controllers;

use App\Events\GenerateGameEvent;
use App\Rank;
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


    public function winGame(Request $request) {
      $userid = $request->input('idUser');

      try {
        $rank = new Rank();
        $rank->status = 1;
        $rank->user_id = $userid;

        $rank->save();
        return response('', 201);
      } catch (\PDOException $ex) {
        return response("Problem with DB ". $ex, 500);
      }

    }
}
