<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TestSubmitEvent;

class SinglePageController extends Controller
{
    public function index() {
        return view('app');
    }

    public function push(Request $request) {
        $idCh = $request->input('idCh');
        $text = $request->input('text');

        event(new TestSubmitEvent($idCh, $text));
    }
}
