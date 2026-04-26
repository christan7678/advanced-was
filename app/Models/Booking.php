<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'booking_code',
        'user_id',
        'event_id',
        'number_of_seats',
        'total_amount',
        'payment_status',
        'booking_status',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function isPaymentExpired()
    {
        if ($this->payment_status !== 'pending') {
            return false;
        }

        if ($this->booking_status === 'cancelled') {
            return false;
        }

        if ($this->expires_at) {
            return $this->expires_at->lt(now());
        }

        return $this->created_at
            && $this->created_at->copy()->addMinutes(1)->lt(now());
    }

    public function expirePaymentIfNeeded()
    {
        if (!$this->isPaymentExpired()) {
            return;
        }

        $this->loadMissing(['event', 'payment']);

        DB::transaction(function () {
            if ($this->event) {
                $this->event->increment('available_seats', $this->number_of_seats);

                $this->event->refresh();

                if ($this->event->available_seats > 0 && $this->event->date && $this->event->date->gte(today())) {
                    $this->event->status = 'active';
                    $this->event->save();
                }
            }

            $this->payment_status = 'cancelled';
            $this->booking_status = 'cancelled';
            $this->save();

            if ($this->payment) {
                $this->payment->update([
                    'status' => 'cancelled',
                ]);
            }
        });
    }



    public function cancelWithRefundIfNeeded()
    {
        if ($this->booking_status === 'cancelled') {
            return;
        }

        if ($this->event) {
            $this->event->increment('available_seats', $this->number_of_seats);
        }

        $this->booking_status = 'cancelled';

        if (in_array($this->payment_status, ['completed'])) {
            $this->payment_status = 'refunded';
        } else {
            $this->payment_status = 'cancelled';
        }

        $this->save();

        $this->loadMissing('ticket');

        if ($this->ticket) {
            $this->ticket->update([
                'status' => 'cancelled',
            ]);
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
        
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
