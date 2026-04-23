<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Only admin and super_admin can view all tickets.
     * Normal users should not access global ticket listings.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'super_admin']);
    }

    /**
     * - Normal users can only view their own tickets
     * - Admin and super_admin can view all tickets
     */
    public function view(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id
            || in_array($user->role, ['admin', 'super_admin']);
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Ticket $ticket)
    {
        return false;
    }

    public function delete(User $user, Ticket $ticket)
    {
        return false;
    }

    public function restore(User $user, Ticket $ticket)
    {
        return false;
    }

    public function forceDelete(User $user, Ticket $ticket)
    {
        return false;
    }
}