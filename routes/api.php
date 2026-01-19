<?php

use App\Http\Controllers\HallController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/halls', [HallController::class, 'index']);

Route::post('/bookings', [BookingController::class, 'store']);
