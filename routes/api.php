<?php

use App\Http\Controllers\HallController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/halls', [HallController::class, 'index']);
Route::get('/halls/search', [HallController::class, 'searchApi']);
Route::get('/check-availability',[BookingController::class, 'check']);
Route::get('/booked-dates', [BookingController::class, 'getBookedDates']);
Route::post('/login', [AuthController::class, 'login']);

// Paymob Webhook (no auth needed)
Route::post('/payments/webhook/paymob', [PaymentController::class, 'paymobWebhook']);

Route::middleware('auth:sanctum')->group(function (){
    //تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout']);
    //حذف الحساب
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::post('/halls', [HallController::class, 'store']);
    Route::middleware('role:owner')->group(function (){
        Route::put('/halls/{id}', [HallController::class, 'update']);
        Route::delete('/halls/{id}', [HallController::class, 'destroy']);
    });
    Route::middleware('role:customer')->group(function (){
        Route::post('/bookings',[BookingController::class,'store']);
    });
    Route::middleware('role:owner,admin')->group(function (){
     Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirmPayment']);
     Route::post('/owner/block-date', [BookingController::class, 'blockDate']);
     Route::post('/bookings/{booking}/confirm-booking', [BookingController::class, 'apiConfirmBooking']);
    });
    Route::middleware('role:customer')->get('/my-bookings', [BookingController::class, 'customerBookings']);
      Route::middleware('role:owner')->get('/owner/bookings', [BookingController::class, 'ownerBookings']);

    // Favorite halls
    Route::middleware('auth:sanctum')->group(function() {
        Route::post('/halls/{hall}/favorite', [HallController::class, 'toggleFavorite']);
        Route::get('/halls/{hall}/favorite', [HallController::class, 'isFavorited']);
    });

    // Notifications routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    });

    // Payment routes
    Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);
    Route::post('/payments/confirm', [PaymentController::class, 'confirmPayment']);
    Route::get('/payments/{booking_id}', [PaymentController::class, 'getPaymentStatus']);
    Route::middleware('role:owner')->post('/bookings/{booking_id}/confirm', [PaymentController::class, 'confirmBooking']);
});

