<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Booking;

class DepositPaidNotification extends Notification
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
        $customerPhone = $this->booking->user->phone;
        
        // Show phone or a helpful message if not available
        if (empty($customerPhone)) {
            $phoneDisplay = 'يرجى التحقق من ملف العميل الشخصي';
        } else {
            $phoneDisplay = $customerPhone;
        }
        
        return (new MailMessage)
            ->subject('تم دفع عربون حجز جديد')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم دفع عربون حجز لقاعتك: ' . $this->booking->hall->name)
            ->line('تاريخ الحجز: ' . $this->booking->booking_date->format('Y-m-d'))
            ->line('اسم العميل: ' . $this->booking->user_name)
            ->line('رقم تلفون العميل: ' . $phoneDisplay)
            ->line('مبلغ العربون: ' . $this->booking->deposit_amount . ' ج.م')
            ->action('عرض تفاصيل الحجز', url('/owner/bookings/' . $this->booking->id))
            ->line('يرجى تأكيد الحجز لتحويل العربون إلى حسابك.')
            ->salutation('مع خالص التحية، فريق Halls Booking');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $customerPhone = $this->booking->user->phone;
        
        // Show phone or a helpful message if not available
        if (empty($customerPhone)) {
            $phoneDisplay = 'يرجى التحقق من ملف العميل الشخصي';
        } else {
            $phoneDisplay = $customerPhone;
        }
        
        return [
            'title' => 'تم دفع عربون حجز جديد',
            'message' => 'تم دفع عربون لقاعتك: ' . $this->booking->hall->name . ' بتاريخ ' . $this->booking->booking_date->format('Y-m-d'),
            'booking_id' => $this->booking->id,
            'hall_name' => $this->booking->hall->name,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'customer_name' => $this->booking->user_name,
            'customer_phone' => $phoneDisplay,
            'deposit_amount' => $this->booking->deposit_amount,
            'type' => 'deposit_paid',
            'action_url' => url('/owner/bookings'),
        ];
    }
}

