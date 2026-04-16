<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    // A promo code can be used in many bookings
    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}