<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index() {
        return Hall::all();
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'main_image' => 'nullable|string',
            'location' =>'required|string',
            'capacity' =>'required|integer'
        ]);

        try {
        $hall = $request->user()->halls()->create($fields);

        return response([
            'status' => 'success',
            'message' => 'تمت إضافة القاعة بنجاح',
            'hall' => $hall
        ], 201);
            } catch (\Exception $e){
                return response(['error'=> $e->getMessage()],500);
            }
    }
}