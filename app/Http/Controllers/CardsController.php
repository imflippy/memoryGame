<?php

namespace App\Http\Controllers;

use App\Cards;
use App\Events\GenerateCards;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function getCards(Request $request) {
      $cards = Cards::all();
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
}
