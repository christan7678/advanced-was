<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Only admin and super_admin can access the full user listing.
     */
    public function viewAny(User $authUser)
    {
        return in_array($authUser->role, ['admin', 'super_admin']);
    }

    /**
     * - Users can view their own profile
     * - Admin can only view normal users
     * - Super_admin can view all users
     */
    public function view(User $authUser, User $user)
    {
        // super_admin can view everything
        if ($authUser->role === 'super_admin') {
            return true;
        }

        // admin can only view normal users
        if ($authUser->role === 'admin') {
            return $user->role === 'user';
        }

        // normal user can only view self
        return $authUser->id === $user->id;
    }

    /**
     * Registration handled by auth system
     */
    public function create(User $authUser)
    {
        return false;
    }

    /**
     * - Users can update own profile
     * - Admin can only update normal users
     * - Super_admin can update all users
     */
    public function update(User $authUser, User $user)
    {
        if ($authUser->role === 'super_admin') {
            return true;
        }

        if ($authUser->role === 'admin') {
            return $user->role === 'user';
        }

        return $authUser->id === $user->id;
    }

    /**
     * - Admin can delete normal users only
     * - Super_admin can delete all users
     */
    public function delete(User $authUser, User $user)
    {
        if ($authUser->role === 'super_admin') {
            return true;
        }

        if ($authUser->role === 'admin') {
            return $user->role === 'user';
        }

        return false;
    }

    /**
     * Same rule as delete
     */
    public function restore(User $authUser, User $user)
    {
        return $this->delete($authUser, $user);
    }

    /**
     * Only super_admin can permanently delete
     */
    public function forceDelete(User $authUser, User $user)
    {
        return $authUser->role === 'super_admin';
    }
}