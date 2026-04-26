<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\HallImage;
use App\Models\Review;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'main_image',
        'location',
        'user_id',
        'capacity',
        'category',
        'features',
        'description',
        'facilities',
        'phone',
        'whatsapp',
        'unavailable_dates',
        'status',
        'min_price',
        'max_price'
    ];

    protected $casts = [
        'features' => 'array',
        'unavailable_dates' => 'array',
    ];

    protected $appends = [
        'main_image_url',
        'favorites_count',
    ];

    public function getMainImageUrlAttribute()
    {
        $path = $this->main_image;
        if (!$path) {
            return 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=600&q=80';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (!Storage::disk('public')->exists($path)) {
            return 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=600&q=80';
        }

        return '/storage-file/' . $path;
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favoritedByUsers()->count();
    }

    public function getFirstImageUrlAttribute()
    {
        $mainUrl = $this->main_image_url;
        if (!str_contains($mainUrl, 'images.unsplash.com')) {
            return $mainUrl;
        }

        foreach ($this->gallery as $image) {
            $path = ltrim($image->path ?? '', '/');
            if ($path && Storage::disk('public')->exists($path)) {
                return '/storage-file/' . $path;
            }
        }

        return $mainUrl;
    }

    // علاقة عكسية: القاعة تنتمي لمستخدم (مالك)
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function gallery()
    {
        return $this->hasMany(HallImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites')->withTimestamps();
    }
}