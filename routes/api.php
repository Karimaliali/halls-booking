<?php

use App\Http\Controllers\HallController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/status', [StatusController::class, 'check']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/halls', [HallController::class, 'index']);
Route::get('/check-availability',[BookingController::class, 'check']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function (){
    //تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);
    //حذف الحساب
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::post('/halls', [HallController::class, 'store']);
    Route::middleware('role:customer')->group(function (){
        Route::post('/bookings',[BookingController::class,'store']);
    });
    Route::middleware('role:owner,admin')->group(function (){
     Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirmPayment']);
     Route::post('/owner/block-date', [BookingController::class, 'blockDate']);
    });
     Route::middleware('role:customer')->get('/my-bookings', [BookingController::class, 'customerBookings']);
      Route::middleware('role:owner')->get('/owner/bookings', [BookingController::class, 'ownerBookings']);
});
