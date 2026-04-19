<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'hall_id',
        'booking_date',
        'status',
        'confirmed_by_owner',
        'user_name',
        'user_id_number',
        'id_card_image',
        'receipt_image',
        'deposit_amount',
        'payment_status',
        'payment_date',
        'payment_expires_at',
        'payment_method',
        'transaction_id'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'payment_date' => 'datetime',
        'payment_expires_at' => 'datetime',
        'deposit_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Check if payment has expired (24 hours passed)
     */
    public function isPaymentExpired(): bool
    {
        if ($this->payment_expires_at && $this->payment_status === 'pending') {
            return now()->greaterThan($this->payment_expires_at);
        }
        return false;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending' && !$this->isPaymentExpired();
    }

    /**
     * Check if payment is completed
     */
    public function isPaymentCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if booking is confirmed by owner
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get total price for booking
     */
    public function getTotalPrice(): float
    {
        return $this->hall->price ?? 0;
    }

    /**
     * Calculate remaining time until payment expires
     */
    public function getRemainingTime(): ?int
    {
        if (!$this->payment_expires_at) {
            return null;
        }

        $remaining = $this->payment_expires_at->diffInMinutes(now());
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Mark payment as refunded
     */
    public function refundPayment()
    {
        $this->payment_status = 'refunded';
        $this->save();
        return $this;
    }

    /**
     * Mark payment as completed
     */
    public function completePayment()
    {
        $this->payment_status = 'completed';
        $this->payment_date = now();
        $this->save();
        return $this;
    }

    /**
     * Mark booking as confirmed
     */
    public function confirmBooking()
    {
        $this->status = 'confirmed';
        $this->payment_status = 'released';
        $this->save();
        return $this;
    }

    /**
     * Mark booking as cancelled
     */
    public function cancelBooking($reason = null)
    {
        $this->status = 'cancelled';
        
        // If payment was completed, mark for refund
        if ($this->payment_status === 'completed') {
            $this->payment_status = 'refunded';
        }
        
        $this->save();
        return $this;
    }

    /**
     * Scopes
     */

    /**
     * Get expired pending payments
     */
    public function scopeExpiredPayments($query)
    {
        return $query->where('payment_status', 'pending')
                     ->whereNotNull('payment_expires_at')
                     ->where('payment_expires_at', '<', now());
    }

    /**
     * Get pending payments that are not expired
     */
    public function scopePendingPayments($query)
    {
        return $query->where('payment_status', 'pending')
                     ->where(function ($q) {
                         $q->whereNull('payment_expires_at')
                           ->orWhere('payment_expires_at', '>=', now());
                     });
    }

    /**
     * Get completed payments
     */
    public function scopeCompletedPayments($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Get refunded payments
     */
    public function scopeRefundedPayments($query)
    {
        return $query->where('payment_status', 'refunded');
    }

    /**
     * Get confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Get pending bookings (not yet confirmed by owner)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get cancelled bookings
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Get bookings by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    /**
     * Get bookings by customer (user)
     */
    public function scopeByCustomer($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get bookings by hall
     */
    public function scopeByHall($query, $hallId)
    {
        return $query->where('hall_id', $hallId);
    }

    /**
     * Get bookings that need owner action (pending, payment completed)
     */
    public function scopeNeedingOwnerAction($query)
    {
        return $query->where('status', 'pending')
                     ->where('payment_status', 'completed');
    }
}
