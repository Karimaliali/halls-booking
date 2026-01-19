<?php

namespace App\Http\Controllers;

use app\Models\Hall;

use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index(){
        $halls = Hall::all();
        return
        response()->json($halls);
    }
}
