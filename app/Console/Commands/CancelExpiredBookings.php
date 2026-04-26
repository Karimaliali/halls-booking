<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bookings that have been paid but not confirmed by owner within 36 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('payment_status', 'completed')
            ->where('status', 'pending')
            ->where('payment_date', '<', now()->subHours(36))
            ->get();

        $count = 0;
        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'cancelled_at' => now(),
                'cancel_reason' => 'لم يتم تأكيد الحجز من قبل المالك خلال 36 ساعة'
            ]);

            // Notify customer about cancellation and refund
            $booking->user->notify(new BookingCancelledNotification($booking));

            Log::info("Cancelled expired booking #{$booking->id} for hall {$booking->hall->name}");
            $count++;
        }

        $this->info("Cancelled {$count} expired bookings");
        return 0;
    }
}