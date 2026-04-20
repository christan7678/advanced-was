<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    protected $fillable = [
        'name',
        'artist',
        'description',
        'date',
        'time',
        'venue',
        'price',
        'total_seats',
        'available_seats',
        'status',
        'category_id',
        'image',
        'organizer',
    ];

    protected static function booted()
    {
        static::saving(function ($event) {
            if ($event->available_seats == 0) {
                $event->status = 'sold_out';
            }
        });
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
