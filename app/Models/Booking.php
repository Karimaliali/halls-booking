<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
         'user_id',
         'hall_id',
         'booking_date',
         'status',
         'deposit_amount',
         'payment_status',
         'payment_date',
         'payment_expires_at',
         'payment_method',
         'transaction_id'
         ];
         public function user(){
            return 
            $this->belongsTo(User::class);
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
     * Mark payment as refunded
     */
    public function refundPayment()
    {
        $this->payment_status = 'refunded';
        $this->save();
        return $this;
    }

    /**
     * Scope to get expired pending payments
     */
    public function scopeExpiredPayments($query)
    {
        return $query->where('payment_status', 'pending')
                     ->whereNotNull('payment_expires_at')
                     ->where('payment_expires_at', '<', now());
    }

