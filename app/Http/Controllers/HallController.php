<?php

namespace App\Http\Controllers;

use App\Models\Hall;

use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index(){
        $halls = Hall::all();
        return
        response()->json($halls);
    }
}
