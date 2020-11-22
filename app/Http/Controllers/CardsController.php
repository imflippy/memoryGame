<?php

namespace App\Http\Controllers;

use App\Cards;
use App\Events\GameEvent;
use App\Events\GenerateCards;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function getCards(Request $request) {
//      $cards = Cards::all();
      $cards = Cards::find([1, 2]);
      $newCards = [];

      foreach ($cards as $c) {
        $newCards[] = $c;
        $newCards[] = $c;
      }
      shuffle($newCards);

      $gameId = (int) $request->query('gameId');
      broadcast(new GenerateCards($gameId, $newCards))->toOthers();

      return response()->json($newCards, 200);

    }

  public function openCard(Request $request) {
    $currentGame = $request->input('currentGame') ?? null;
    $cardDetails = $request->input('cardDetails') ?? null;
    $gameId = $request->input('gameId');

    $currentRoundCardsKeys = $currentGame['currentRoundCardsKeys'] ?? [];
    $currentRoundCards = $currentGame['currentRoundCards'] ?? [];

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

    broadcast(new GameEvent($data));

    return response()->json("Game logic triggered", 200);

  }
}
