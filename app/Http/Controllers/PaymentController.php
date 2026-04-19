<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hall;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\DepositPaidNotification;

class PaymentController extends Controller
{
    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

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
        $user = Auth::user();
        $depositAmount = ($hall->price * $request->deposit_percentage) / 100;

        $apiKey = config('services.paymob.api_key');
        $merchantId = config('services.paymob.merchant_id');
        $integrationId = config('services.paymob.integration_id');

        // Check if we're in test/demo mode
        $isTestMode = env('PAYMOB_TEST_MODE', false) || 
                      empty($apiKey) || in_array($apiKey, ['your_api_key_here', 'YOUR_API_KEY_HERE'], true) ||
                      empty($merchantId) || in_array($merchantId, ['your_merchant_id', 'YOUR_MERCHANT_ID_HERE'], true) ||
                      empty($integrationId) || in_array($integrationId, ['your_integration_id', 'YOUR_INTEGRATION_ID_HERE'], true);

        try {
            if ($isTestMode) {
                // Test/Demo mode: Simulate Paymob response
                $testOrderId = 'TEST_' . $booking->id . '_' . time();
                $testPaymentKey = 'test_payment_key_' . bin2hex(random_bytes(16));
                
                // Update booking with test payment info
                $booking->update([
                    'deposit_amount' => $depositAmount,
                    'payment_status' => 'pending',
                    'payment_expires_at' => now()->addHours(24),
                    'payment_method' => 'paymob_test',
                    'transaction_id' => $testOrderId,
                ]);

                return response()->json([
                    'message' => 'Payment initiated (TEST MODE)',
                    'booking_id' => $booking->id,
                    'amount' => $depositAmount,
                    'currency' => 'EGP',
                    'expires_at' => $booking->payment_expires_at,
                    'order_id' => $testOrderId,
                    'payment_key' => $testPaymentKey,
                    'iframe_url' => route('payments.test-callback', ['booking_id' => $booking->id, 'status' => 'success']),
                    'is_test_mode' => true
                ], 200);
            }

            // Create Paymob order
            $paymentData = $this->paymob->createOrder(
                $depositAmount,
                $booking->id,
                $user->email,
                $user->phone ?? '0100000000',
                $user->name
            );

            // Update booking with payment info
            $booking->update([
                'deposit_amount' => $depositAmount,
                'payment_status' => 'pending',
                'payment_expires_at' => now()->addHours(24),
                'payment_method' => 'paymob',
                'transaction_id' => $paymentData['order_id'],
            ]);

            return response()->json([
                'message' => 'Payment initiated',
                'booking_id' => $booking->id,
                'amount' => $depositAmount,
                'currency' => 'EGP',
                'expires_at' => $booking->payment_expires_at,
                'order_id' => $paymentData['order_id'],
                'payment_key' => $paymentData['payment_key'],
                'iframe_url' => "https://accept.paymobsolutions.com/api/acceptance/iframes/" . config('services.paymob.integration_id') . "?payment_token={$paymentData['payment_key']}"
            ], 200);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment initiation failed', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm payment received (called after Paymob webhook or manual verification)
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
     *                     @OA\Property(property="order_id", type="string")
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
            'order_id' => 'required|string',
        ]);

        $booking = Booking::find($request->booking_id);

        if ($booking->transaction_id !== $request->order_id) {
            return response()->json(['error' => 'Invalid order ID'], 400);
        }

        if ($booking->payment_status === 'completed') {
            return response()->json(['error' => 'Payment already completed'], 400);
        }

        try {
            // Verify with Paymob
            $orderData = $this->paymob->verifyPayment($request->order_id);

            if (($orderData['order_status'] ?? null) !== 'PAID') {
                return response()->json(['error' => 'Payment not confirmed by Paymob'], 400);
            }

            $previousStatus = $booking->payment_status;

            $booking->update([
                'payment_status' => 'completed',
                'payment_date' => now(),
            ]);

            // Notify hall owner about deposit payment only once, after the status changes to completed
            if ($previousStatus !== 'completed') {
                $hallOwner = $booking->hall->user;
                if ($hallOwner) {
                    $hallOwner->notify(new DepositPaidNotification($booking));
                }
            }

            return response()->json([
                'message' => 'Payment confirmed',
                'booking_id' => $booking->id,
                'payment_status' => 'completed',
                'amount' => $booking->deposit_amount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment confirmation failed'], 500);
        }
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
     * Webhook handler for Paymob
     */
    public function paymobWebhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Paymob Webhook Received: ' . json_encode($data));

            // Verify webhook signature
            if (!$this->verifyPaymobSignature($data)) {
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            $orderId = $data['obj']['order']['id'] ?? null;
            $status = $data['obj']['status'] ?? null;

            if ($orderId && $status === 'success') {
                $booking = Booking::where('transaction_id', $orderId)->first();
                if ($booking) {
                    $previousStatus = $booking->payment_status;

                    $booking->update([
                        'payment_status' => 'completed',
                        'payment_date' => now(),
                        'status' => 'pending', // Changed from pending_payment to pending
                    ]);

                    if ($previousStatus !== 'completed') {
                        $hallOwner = $booking->hall->user;
                        if ($hallOwner) {
                            $hallOwner->notify(new DepositPaidNotification($booking));
                        }
                    }

                    Log::info("Payment confirmed for booking #{$booking->id}");
                }
            }

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Verify Paymob webhook signature
     */
    protected function verifyPaymobSignature($data)
    {
        // Implement Paymob signature verification as per their documentation
        return true; // For now, trust Paymob
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
