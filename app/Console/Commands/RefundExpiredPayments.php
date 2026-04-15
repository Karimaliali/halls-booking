<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\PaymobService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefundExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:refund-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically refund payments that have expired (24 hours without owner confirmation)';

    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        parent::__construct();
        $this->paymob = $paymob;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find all bookings with expired pending payments
        $expiredBookings = Booking::expiredPayments()->get();

        $refundedCount = 0;
        $failedCount = 0;

        foreach ($expiredBookings as $booking) {
            try {
                $this->info("Processing refund for booking #{$booking->id}...");

                // Get transaction details from Paymob
                if ($booking->payment_method === 'paymob' && $booking->transaction_id) {
                    // Attempt refund via Paymob
                    $refundResult = $this->paymob->refundPayment(
                        $booking->transaction_id,
                        $booking->deposit_amount
                    );

                    $booking->refundPayment();
                    $refundedCount++;

                    Log::info("Refunded payment for booking #{$booking->id} - Amount: {$booking->deposit_amount} EGP");
                    $this->info("✓ Refunded {$booking->deposit_amount} EGP for booking #{$booking->id}");
                } else {
                    $booking->refundPayment();
                    $refundedCount++;
                    $this->info("✓ Marked as refunded for booking #{$booking->id}");
                }
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to refund booking #{$booking->id}: " . $e->getMessage());
                $this->error("✗ Failed to refund booking #{$booking->id}: " . $e->getMessage());
            }
        }

        if ($refundedCount === 0 && $failedCount === 0) {
            $this->info('No expired payments to refund.');
        } else {
            $this->info("\n================================");
            $this->info("Refunds Summary:");
            $this->info("  Successful: {$refundedCount}");
            $this->info("  Failed: {$failedCount}");
            $this->info("================================");
        }

        return 0;
    }
}
