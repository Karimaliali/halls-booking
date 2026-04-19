<?php

namespace App\Http\Controllers;

use App\Models\Booking; 
use Illuminate\Http\Request;
use App\Notifications\NewBookingNotification;
use App\Notifications\BookingConfirmedNotification;
use App\Models\Withdrawal;

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

        // Check for active bookings on the selected date. This includes confirmed bookings,
        // completed payments, and pending payments that have not yet expired.
        $exists = $this->activeBookingQuery($validated['hall_id'], $validated['booking_date'])
            ->exists();

        if ($exists) {
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
            'user_id'           => $request->user()->id,
            'hall_id'           => $validated['hall_id'],
            'booking_date'      => $validated['booking_date'],
            'status'            => 'pending',
            'user_name'         => $validated['userName'] ?? null,
            'user_id_number'    => $validated['userId'] ?? null,
            'id_card_image'     => $idCardPath,
            'receipt_image'     => $receiptPath,
            'payment_status'    => 'pending',
            'payment_expires_at' => now()->addHours(24),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => 'تم إرسال طلب الحجز بنجاح! سيتم مراجعته من قبل الإدارة.',
                'booking_id' => $booking->id,
                'booking' => $booking,
            ]);
        }

        return back()->with('success', 'تم إرسال طلب الحجز بنجاح! سيتم مراجعته من قبل الإدارة.');
    }

    public function check(Request $request)
    {
        $isBooked = $this->activeBookingQuery($request->hall_id, $request->booking_date)
            ->exists();

        return response()->json(['available' => ! $isBooked]);
    }

    /**
     * Get all booked dates for a hall
     */
    public function getBookedDates(Request $request)
    {
        $hallId = $request->hall_id;

        $bookedDates = $this->activeBookingsForHallQuery($hallId)
            ->pluck('booking_date')
            ->map(fn($date) => $date->toDateString())
            ->toArray();

        return response()->json(['booked_dates' => array_unique($bookedDates)]);
    }

    /**
     * Build the query for active bookings that should block a hall/date.
     * Includes:
     * - Confirmed bookings
     * - Bookings with completed payment
     * - Pending bookings (both pending payment and pending confirmation by owner) until they expire
     */
    private function activeBookingsForHallQuery($hallId)
    {
        return Booking::where('hall_id', $hallId)
            ->where(function ($query) {
                $query->where('status', 'confirmed')
                    ->orWhere('payment_status', 'completed')
                    ->orWhere(function ($query) {
                        // Include pending bookings that haven't expired
                        $query->whereIn('status', ['pending', 'pending_payment'])
                            ->where(function ($query) {
                                $query->whereNull('payment_expires_at')
                                    ->orWhere('payment_expires_at', '>=', now());
                            });
                    });
            });
    }

    /**
     * Build the query for a specific hall/date combination.
     */
    private function activeBookingQuery($hallId, $bookingDate)
    {
        return $this->activeBookingsForHallQuery($hallId)
            ->where('booking_date', $bookingDate);
    }

    public function confirmPayment($bookingId)
    {
        $booking = Booking::with(['user','hall.owner'])->findOrFail($bookingId);
        $booking->update(['status' => 'confirmed']);

        // إزالة إرسال الإشعار هنا - سيتم إرساله من PaymentController بعد الدفع الفعلي
        // $booking->hall->owner->notify(new NewBookingNotification($booking));
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

        $booking->update([
            'status' => 'confirmed',
            'confirmed_by_owner' => true
        ]);

        // Create withdrawal for the deposit amount to the hall owner
        if ($booking->payment_status === 'completed' && $booking->deposit_amount > 0) {
            Withdrawal::create([
                'booking_id' => $booking->id,
                'hall_owner_id' => $booking->hall->user_id,
                'amount' => $booking->deposit_amount,
                'status' => 'pending', // Will be processed by admin
                'requested_at' => now(),
            ]);
        }

        $booking->user->notify(new BookingConfirmedNotification($booking));

        return back()->with('success', 'تم تأكيد الحجز بنجاح وإنشاء طلب سحب للعربون.');
    }

    /**
     * API endpoint for hall owner to confirm booking
     */
    public function apiConfirmBooking(Request $request, Booking $booking)
    {
        abort_if($booking->hall->user_id !== auth()->id(), 403);

        if ($booking->status !== 'pending') {
            return response()->json(['error' => 'يمكن فقط تأكيد الحجوزات التي في حالة قيد الانتظار.'], 400);
        }

        $booking->update([
            'status' => 'confirmed',
            'confirmed_by_owner' => true
        ]);

        // Create withdrawal for the deposit amount to the hall owner
        if ($booking->payment_status === 'completed' && $booking->deposit_amount > 0) {
            Withdrawal::create([
                'booking_id' => $booking->id,
                'hall_owner_id' => $booking->hall->user_id,
                'amount' => $booking->deposit_amount,
                'status' => 'pending', // Will be processed by admin
                'requested_at' => now(),
            ]);
        }

        $booking->user->notify(new BookingConfirmedNotification($booking));

        return response()->json([
            'message' => 'تم تأكيد الحجز بنجاح وإنشاء طلب سحب للعربون.',
            'booking' => $booking->load('hall', 'user')
        ]);
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