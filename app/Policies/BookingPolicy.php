<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// lets admin/super_admin view and cancel bookings
// normal user only handles own bookings
class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Admin and super_admin can view booking lists.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'super_admin']);
    }

    /**
     * Admin/super_admin can view all bookings.
     * Normal user can only view own booking.
     */
    public function view(User $user, Booking $booking)
    {
        return $user->role === 'admin'
            || $user->role === 'super_admin'
            || $user->id === $booking->user_id;
    }

    /**
     * Keep open for authenticated users.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Only admin and super_admin can update bookings.
     */
    public function update(User $user, Booking $booking)
    {
        return in_array($user->role, ['admin', 'super_admin']);
    }

    /**
     * Admin/super_admin can cancel any booking.
     * Normal user can cancel own booking.
     */
    public function delete(User $user, Booking $booking)
    {
        return $user->role === 'admin'
            || $user->role === 'super_admin'
            || $user->id === $booking->user_id;
    }

    /**
     * Only admin and super_admin can restore.
     */
    public function restore(User $user, Booking $booking)
    {
        return in_array($user->role, ['admin', 'super_admin']);
    }

    /**
     * Only super_admin can force delete.
     */
    public function forceDelete(User $user, Booking $booking)
    {
        return $user->role === 'super_admin';
    }

    /**
     * Normal user can only pay own booking.
     */
    public function pay(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id
            && $booking->payment_status === 'pending';
    }
}