<?php

namespace App\Http\Controllers;

use App\Models\Booking; 
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'hall_id'      => 'required|exists:halls,id',
            'booking_date' => 'required|date',
        ]);

       
        $booking = Booking::create([
            'user_id'      => $validated['user_id'],
            'hall_id'      => $validated['hall_id'],
            'booking_date' => $validated['booking_date'],
            'status'       => 'pending', // حالة الطلب الافتراضية
        ]);

        
        return response()->json([
            'message' => 'Booking request sent successfully!',
            'booking' => $booking
        ], 201);
    }
}