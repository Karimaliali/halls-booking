<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingConfirmedNotification extends Notification
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $hallName = $this->booking->hall ? $this->booking->hall->name : 'غير محدد';

        return (new MailMessage)
            ->subject('تأكيد حجزك')
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('تم تأكيد حجزك للقاعة "' . $hallName . '".')
            ->line('تفاصيل الحجز:')
            ->line('التاريخ: ' . $this->booking->booking_date)
            ->line('الحالة: ' . $this->booking->status)
            ->action('عرض الحجز', url('/user/bookings/' . $this->booking->id))
            ->line('شكراً لاستخدامك منصتنا!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'hall_name' => $this->booking->hall ? $this->booking->hall->name : null,
            'booking_date' => $this->booking->booking_date,
            'status' => $this->booking->status,
        ];
    }
}
