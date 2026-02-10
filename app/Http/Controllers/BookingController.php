<?php

namespace App\Http\Controllers;

use App\Models\Booking; 
use Illuminate\Http\Request;
use App\Notifications\NewBookingNotification;
use App\Notifications\BookingConfirmedNotification;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hall_id'      => 'required|exists:halls,id',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);
        $exists = Booking::where('hall_id',$validated['hall_id'])
        ->where('booking_date',$validated['booking_date'])
        ->exists();
        if($exists){
            return response()->json(['message'=>'عفواً القاعة محجوزة بالفعل في هذا التاريخ'], 422);
        }
       
        $booking = Booking::create([
            'user_id'      => $request->user()->id,
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
        $isBooked = Booking::where('hall_id',$request->hall_id)
        ->where('booking_date',$request->booking_date)
        ->exists();
        return response()->json(['available'=> ! $isBooked]);
    }

    public function confirmPayment($bookingId)
    {
        $booking = Booking::with(['user','hall.owner'])->findOrFail($bookingId);
        $booking->update(['status' => 'confirmed']);

        $booking->hall->owner->notify(new NewBookingNotification($booking));
        $booking->user->notify(new BookingConfirmedNotification($booking));
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new \App\Notifications\NewBookingNotification($booking));
        }

        return response()->json([
            'message' => 'تم تأكيد الدفع وإشعار المدير بنجاح',
            'booking' => $booking
        ]);
    }

    public function customerBookings(Request $request){
        $bookings = Booking::where('hall')->where('user_id',$request->user()->id)
        ->latest()->get();
        return response()->json(['my_bookings'=> $bookings]);
    }

    public function ownerBookings(Request $request){
        $bookings = Booking::whereHas('hall', function($query) use ($request){
            $query->where('user_id',$request->user()->id);
        })->with('user')
        ->latest()->get();
        return response()->json(['incoming_requests'=> $bookings]);
    }

    public function blockDate(Request $request)
    {
        return response()->json(['message' => 'تم حجز التاريخ بنجاح'], 200);
    }
}