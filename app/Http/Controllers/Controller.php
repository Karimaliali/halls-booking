<?php

namespace App\Http\Controllers;

abstract class Controller
{
    
    public function ping() {
        return response()->json(['pong' => true]);
    }
}