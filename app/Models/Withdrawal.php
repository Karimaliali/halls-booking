<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'bank_account',
        'bank_name',
        'account_holder',
        'notes',
        'requested_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function complete()
    {
        $this->update(['status' => 'completed', 'completed_at' => now()]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
}
