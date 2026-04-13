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
        if ($request->user()->role !== 'customer') {
            abort(403, 'غير مسموح للمالك بتقديم طلب حجز.');
        }

        $validated = $request->validate([
            'hall_id'      => 'required|exists:halls,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'userName'     => 'nullable|string|max:255',
            'userId'       => 'nullable|string|max:14',
            'idCardImage'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'receiptImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $exists = Booking::where('hall_id',$validated['hall_id'])
        ->where('booking_date',$validated['booking_date'])
        ->exists();

        if($exists){
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'عفواً القاعة محجوزة بالفعل في هذا التاريخ'], 422);
            }
            return back()->with('error', 'عفواً القاعة محجوزة بالفعل في هذا التاريخ');
        }

        // معالجة الصور
        $idCardPath = null;
        $receiptPath = null;

        if ($request->hasFile('idCardImage')) {
            $idCardPath = $request->file('idCardImage')->store('bookings/id_cards', 'public');
        }

        if ($request->hasFile('receiptImage')) {
            $receiptPath = $request->file('receiptImage')->store('bookings/receipts', 'public');
        }

        $booking = Booking::create([
            'user_id'      => $request->user()->id,
            'hall_id'      => $validated['hall_id'],
            'booking_date' => $validated['booking_date'],
            'status'       => 'pending',
            'user_name'    => $validated['userName'] ?? null,
            'user_id_number' => $validated['userId'] ?? null,
            'id_card_image' => $idCardPath,
            'receipt_image' => $receiptPath,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'تم إرسال طلب الحجز بنجاح! سيتم مراجعته من قبل الإدارة.']);
        }

        return back()->with('success', 'تم إرسال طلب الحجز بنجاح! سيتم مراجعته من قبل الإدارة.');
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

    public function confirmBooking(Booking $booking)
    {
        abort_if($booking->hall->user_id !== auth()->id(), 403);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'يمكن فقط تأكيد الحجوزات التي في حالة قيد الانتظار.');
        }

        $booking->update(['status' => 'confirmed']);
        $booking->user->notify(new BookingConfirmedNotification($booking));

        return back()->with('success', 'تم تأكيد الحجز بنجاح.');
    }

    public function cancelBooking(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'تم إلغاء الحجز بالفعل.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الحجز بنجاح.');
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