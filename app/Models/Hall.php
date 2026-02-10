<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'main_image',
        'location',
        'user_id',
        'capacity' // مهم جداً لاستقبال معرف المالك
    ];

    // علاقة عكسية: القاعة تنتمي لمستخدم (مالك)
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function owner() {
        return $this->belongsTo(User::class,'user_id');
    }
}