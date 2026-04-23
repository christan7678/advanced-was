<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $lastVisit = $request->cookie('last_visit');
        return view('profile.index', compact('lastVisit'));
    }

    public function showDetail(Request $request)
    {
        $user = $request->user();
        return view('profile.detail', compact('user'));
    }

    public function tickets(Request $request)
    {
        $user = $request->user();

        $tickets = $user->bookings()
            ->with(['event', 'ticket'])
            ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
            ->orderByRaw("
                CASE
                    WHEN bookings.payment_status = 'completed' THEN 0
                    WHEN bookings.payment_status = 'pending' THEN 1
                    WHEN bookings.payment_status = 'refunded' THEN 2
                    WHEN bookings.payment_status = 'cancelled' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('events.date', 'asc')
            ->select('bookings.*')
            ->get();

        return view('profile.tickets', compact('user', 'tickets'));
    }

    public function history(Request $request)
    {
        $user = $request->user();
        return view('profile.history', compact('user'));
    }

    public function password(Request $request)
    {
        return view('profile.password');
    }

     public function updatePassword(Request $request)
    {
        //force new password to be different from current password
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'Current password is incorrect.',
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.different' => 'New password must be different from current password.',
        ]);

        $request->user()->update([
            'password' =>  Hash::make($request->password),
        ]);

        return redirect()->route('profile.password')
            ->with('success', 'Password updated successfully!');
    }
}