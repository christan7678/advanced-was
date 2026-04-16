<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'date',
        'time',
        'venue',
        'price',
        'total_seats',
        'available_seats',
        'category_id',
        'image',
        'organizer',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
