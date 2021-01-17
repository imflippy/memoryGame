<?php

namespace App\Http\Controllers;

use App\Cards;
use App\Events\GameEvent;
use App\Events\GenerateCards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardsController extends Controller
{
    public function getCards(Request $request) {
      $validator = Validator::make($request->all(), [
        'gameId' => 'required|integer'
      ]);
      if($validator->fails()){
        return response()->json($validator->errors(), 400);
      }
      $cards = Cards::all();
//      $cards = Cards::find([1, 2]);
      $newCards = [];

      foreach ($cards as $c) {
        $newCards[] = $c;
        $newCards[] = $c;
      }
      shuffle($newCards);

      $gameId = (int) $request->query('gameId');
      broadcast(new GenerateCards($gameId, $newCards));

//      return response()->json($newCards, 200);
      return response()->json("APi Respnse getCards", 200);

    }

  public function openCard(Request $request) {
    $validator = Validator::make($request->all(), [
      'gameId' => 'required|integer',
      'startedGameTimestamp' => 'required|integer',
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }

    $currentGame = $request->input('currentGame') ?? null;
    $cardDetails = $request->input('cardDetails') ?? null;
    $gameId = $request->input('gameId');
    $startedGameTimestamp = $request->input('startedGameTimestamp');

    $currentRoundCardsKeys = $currentGame['currentRoundCardsKeys'] ?? [];
    $currentRoundCards = $currentGame['currentRoundCards'] ?? [];
    $stopwatchTime = time() - (int) $startedGameTimestamp;

    if($currentGame != null) {
      array_push($currentRoundCardsKeys, $cardDetails['positionId']);
      array_push($currentRoundCards, $cardDetails['cardId']);
    }


    $changeUser = false;
    $sameCardId = 0;
    if(count($currentRoundCardsKeys) == 2) {
      if($currentRoundCards[0] == $currentRoundCards[1]) {
        $sameCardId = $currentRoundCards[0];
      } else {
        $changeUser = true;
      }
    }


    $data = new \stdClass();
    $data->gameId = (int) $gameId;
    $data->currentRoundCardsKeys = $currentRoundCardsKeys;
    $data->currentRoundCards = $currentRoundCards;
    $data->changeUser = $changeUser;
    $data->sameCardId = $sameCardId;
    $data->stopwatchTime = $stopwatchTime;

    try {
      broadcast(new GameEvent($data));
      return response()->json("Game logic triggered", 200);
    } catch (\PDOException $ex) {
      //log it here
      return response()->json("We will fix this As soon as possible, Sorry...", 500);
    }


  }
}
