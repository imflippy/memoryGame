<?php

namespace App\Http\Controllers;

use App\Events\GenerateGameEvent;
use App\Events\LeaderboardEvent;
use App\Rank;
use App\User;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function generateGame(Request $request) {
      $validator = Validator::make($request->all(), [
        'players' => 'required|array'
      ]);
      if($validator->fails()){
        return response()->json($validator->errors(), 400);
      }
      $players = $request->input('players');
      if (count($players) < 2) {
        return response()->json("Nema dovoljno igraca za kreiranje igre", 422);
      }
      $roomId = rand();

      $roomPlayersKeys = array_rand($players, 2);
      $roomPlayersIds = [];

      foreach ($roomPlayersKeys as $key) {
        $roomPlayersIds[] = $players[$key]['id'];
      }
      broadcast(new GenerateGameEvent($roomPlayersIds, $roomId));
    }


    public function winGame(Request $request) {
      $validator = Validator::make($request->all(), [
        'idUser' => 'required|integer'
      ]);
      if($validator->fails()){
        return response()->json($validator->errors(), 400);
      }
      $userid = $request->input('idUser');

      try {
        $user = User::find($userid);
        $user->wins = $user->wins + 1;
        $user->save();
        $this->leaderboard();
        return response('', 201);
      } catch (\PDOException $ex) {
        return response("Problem with DB ". $ex, 500);
      }

    }

    public function leaderboard (){
        $rows = User::orderBy('wins', 'DESC')->select('name', 'wins')->take(50)->get();
        $currentDateTime = date('Y-m-d H:i:s');

      $res = [
          'rows' => $rows,
          'time' => $currentDateTime
        ];
        try {
          broadcast(new LeaderboardEvent($res));
          return response($res);
        } catch (\PDOException $ex) {
          return response('Problem'. $ex, 500);
        }
    }
}
