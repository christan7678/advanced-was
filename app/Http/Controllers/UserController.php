<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('profile.tickets', compact('user'));
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
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'Current password is incorrect.',
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('profile.password')
            ->with('success', 'Password updated successfully!');
    }
}