<?php

namespace App\Http\Controllers;

use App\Models\Booking; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Facades\NewBookingNotification;
class BookingController extends Controller
{
    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'hall_id'      => 'required|exists:halls,id',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);
        $exists = Booking::where('hall_id',$validated['hall_id'])
        ->wher('booking_date',$validated['booking_date'])
        ->exists();
        if($exists){
            return response()->json(['messagr'=>'عفواً القاعة محجوزة بالفعل في هذا التاريخ'], 422);
        }
       
        $booking = Booking::create([
            'user_id'      => $validated['user_id'],
            'hall_id'      => $validated['hall_id'],
            'booking_date' => $validated['booking_date'],
            'status'       => 'pending',
        ]);

        
        return response()->json([
            'message' => 'Booking request sent successfully!',
            'booking' => $booking
        ], 201);
    }
    public function check(Request $request){
        $isBooked = Booking::wher('hall_id',$request->hall_id)
        ->wher('booking_date',$request->booking_date)
        ->exists();
        return response()->json(['available'=> ! $isBooked]);
    }
    public function confirmPayment($bookingId)
{
    // 1. البحث عن الحجز المطلوب
    $booking = Booking::findOrFail($bookingId);

    // 2. تحديث الحالة لـ مؤكد بعد نجاح الدفع
    $booking->update(['status' => 'confirmed']);

    // 3. إرسال الإشعار للمدير (الكود اللي سألت عليه)
    $admin = \App\Models\User::where('role', 'admin')->first();
    if ($admin) {
        $admin->notify(new \App\Notifications\NewBookingNotification($booking));
    }

    return response()->json([
        'message' => 'تم تأكيد الدفع وإشعار المدير بنجاح',
        'booking' => $booking
    ]);
}

}