<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
         'user_id',
         'hall_id',
         'booking_date',
         'status'
         ];
         public function user(){
            return 
            $this->belongsTo(User::class);
         }
          public function hall(){
            return 
            $this->belongsTo(hall::class);
         }
}
