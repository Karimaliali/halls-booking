<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Initialize payment for a booking
     * 
     * @OA\Post(
     *     path="/api/payments/initiate",
     *     summary="Initiate deposit payment",
     *     tags={"Payments"},
     *     @OA\RequestBody(
     *         required=true,
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(property="booking_id", type="integer", example=1),
     *                     @OA\Property(property="deposit_percentage", type="number", example=25)
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(status=200, description="Payment initiated successfully"),
     *     @OA\Response(status=404, description="Booking not found"),
     *     @OA\Response(status=403, description="Unauthorized")
     * )
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'deposit_percentage' => 'required|numeric|min:10|max:100',
        ]);

        $booking = Booking::find($request->booking_id);

        // Verify ownership
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already paid
        if ($booking->payment_status === 'completed') {
            return response()->json(['error' => 'Payment already completed'], 400);
        }

        $hall = $booking->hall;
        $depositAmount = ($hall->price * $request->deposit_percentage) / 100;

        // Update booking with payment info
        $booking->update([
            'deposit_amount' => $depositAmount,
            'payment_status' => 'pending',
            'payment_expires_at' => now()->addHours(24),
            'payment_method' => 'gateway', // or stripe, paypal, etc.
            'transaction_id' => 'TXN_' . uniqid(),
        ]);

        return response()->json([
            'message' => 'Payment initiated',
            'booking_id' => $booking->id,
            'amount' => $depositAmount,
            'currency' => 'SAR',
            'expires_at' => $booking->payment_expires_at,
            'transaction_id' => $booking->transaction_id,
        ], 200);
    }

    /**
     * Confirm payment received
     * 
     * @OA\Post(
     *     path="/api/payments/confirm",
     *     summary="Confirm deposit payment",
     *     tags={"Payments"},
     *     @OA\RequestBody(
     *         required=true,
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(property="booking_id", type="integer"),
     *                     @OA\Property(property="transaction_id", type="string")
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(status=200, description="Payment confirmed"),
     *     @OA\Response(status=404, description="Booking not found")
     * )
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'transaction_id' => 'required|string',
        ]);

        $booking = Booking::find($request->booking_id);

        if ($booking->transaction_id !== $request->transaction_id) {
            return response()->json(['error' => 'Invalid transaction ID'], 400);
        }

        $booking->update([
            'payment_status' => 'completed',
            'payment_date' => now(),
        ]);

        return response()->json([
            'message' => 'Payment confirmed',
            'booking_id' => $booking->id,
            'payment_status' => 'completed',
            'amount' => $booking->deposit_amount,
        ], 200);
    }

    /**
     * Get payment status for a booking
     * 
     * @OA\Get(
     *     path="/api/payments/{booking_id}",
     *     summary="Get payment status",
     *     tags={"Payments"},
     *     @OA\Parameter(name="booking_id", in="path", required=true),
     *     @OA\Response(status=200, description="Payment status retrieved"),
     *     @OA\Response(status=404, description="Booking not found")
     * )
     */
    public function getPaymentStatus($bookingId)
    {
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json([
            'booking_id' => $booking->id,
            'payment_status' => $booking->payment_status,
            'deposit_amount' => $booking->deposit_amount,
            'payment_date' => $booking->payment_date,
            'expires_at' => $booking->payment_expires_at,
            'is_expired' => $booking->isPaymentExpired(),
        ], 200);
    }

    /**
     * Owner confirms booking (which triggers payment release)
     * 
     * @OA\Post(
     *     path="/api/bookings/{booking_id}/confirm",
     *     summary="Owner confirms booking",
     *     tags={"Bookings"},
     *     @OA\Parameter(name="booking_id", in="path", required=true),
     *     @OA\Response(status=200, description="Booking confirmed, payment released"),
     *     @OA\Response(status=403, description="Unauthorized")
     * )
     */
    public function confirmBooking($bookingId)
    {
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        // Verify ownership (owner of the hall)
        if ($booking->hall->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status === 'confirmed') {
            return response()->json(['error' => 'Booking already confirmed'], 400);
        }

        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'released', // Payment released to owner
        ]);

        return response()->json([
            'message' => 'Booking confirmed and payment released to owner',
            'booking_id' => $booking->id,
            'status' => 'confirmed',
            'deposit_amount' => $booking->deposit_amount,
            'owner_id' => $booking->hall->user_id,
        ], 200);
    }
}
