<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find all bookings with expired pending payments
        $expiredBookings = Booking::expiredPayments()->get();

        $refundedCount = 0;

        foreach ($expiredBookings as $booking) {
            $booking->refundPayment();
            $refundedCount++;

            $this->info("Refunded payment for booking #{$booking->id} - Amount: {$booking->deposit_amount} SAR");
        }

        if ($refundedCount === 0) {
            $this->info('No expired payments to refund.');
        } else {
            $this->info("Total refunds processed: {$refundedCount}");
        }

        return 0;
    }
}
