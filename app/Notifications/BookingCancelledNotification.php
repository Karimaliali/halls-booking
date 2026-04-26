<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Booking;

class BookingCancelledNotification extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم إلغاء حجزك تلقائياً')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم إلغاء حجزك لقاعة: ' . $this->booking->hall->name . ' تلقائياً')
            ->line('السبب: لم يتم تأكيد الحجز من قبل المالك خلال 36 ساعة من الدفع')
            ->line('تم استرداد مبلغ العربون إلى حسابك')
            ->line('تاريخ الحجز: ' . $this->booking->booking_date->format('Y-m-d'))
            ->action('عرض حجوزاتي', url('/customer/bookings'))
            ->salutation('مع خالص التحية، فريق Halls Booking');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'تم إلغاء حجزك تلقائياً',
            'message' => 'تم إلغاء حجزك لقاعة ' . $this->booking->hall->name . ' بسبب عدم تأكيد المالك خلال 36 ساعة. تم استرداد العربون.',
            'booking_id' => $this->booking->id,
            'hall_name' => $this->booking->hall->name,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'reason' => 'لم يتم تأكيد الحجز من قبل المالك خلال 36 ساعة',
            'type' => 'booking_cancelled',
            'action_url' => url('/customer/bookings'),
        ];
    }
}