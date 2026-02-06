<?php

use App\Http\Controllers\HallController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/halls', [HallController::class, 'index']);
Route::get('/check-availability',[BookingController::class, 'check']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function (){
    Route::middleware('role:customer')->group(function (){
        Route::post('/bookings',[BookingController::class,'store']);
    });
    Route::middleware('role:owner,admin')->group(function (){
     Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirmpayment']);
     Route::post('/owner/block-date', [BookingController::class, 'blockDate']);
    });
});
