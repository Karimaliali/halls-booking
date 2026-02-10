<?php

namespace App\Http\Controllers;

class StatusController extends Controller
{
    public function check()
    {
        return response()->json(['status' => 'ok']);
    }
}
