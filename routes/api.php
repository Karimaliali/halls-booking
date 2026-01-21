<?php

use App\Http\Controllers\HallController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/halls', [HallController::class, 'index']);
Route::get('/check-availability',[BookingController::class, 'check']);

Route::middleware('auth:sanctum')->group(function (){
    Route::middlware('role:customer')->group(function (){
        Route::post('/bookings',[BookingController::class,'store']);
    });
    Route::middlware('role:owner,admin')->group(function (){
     Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirmpayment']);
     Route::post('/owner/block-date', [BookingController::class, 'blockDate']);
    });
});
