<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // Get all users
        $usersQuery = User::query();

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        $users = $usersQuery->latest()->get();

        // For now, return empty collection for admins since there's no admin table
        $admins = collect([]);

        return view('admin.users.index', compact('users', 'admins', 'q'));
    }

    public function searchUsers(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bookings_count' => $user->bookings()->count(),
                    'created_at' => $user->created_at ? $user->created_at->format('d M Y') : '—',
                ];
            });

        return response()->json(['users' => $users]);
    }

    public function show(User $user)
    {
        $user->load(['bookings.event.category']);

        return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account created successfully.'], 201);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Account created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            $user->update($validated);

            if ($request->filled('password')) {
                $pwValidated = $request->validate([
                    'password' => 'required|string|min:6|confirmed',
                ]);

                $user->update([
                    'password' => bcrypt($pwValidated['password']),
                ]);
            }

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Account updated successfully.'
                ], 200);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Account updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getBookings(User $user)
    {
        $bookings = $user->bookings()->with('event.category')->latest()->get();

        return response()->json([
            'bookings' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'event_name' => $booking->event->name ?? '—',
                    'quantity' => $booking->number_of_seats,
                    'category' => $booking->event->category->name ?? '—',
                    'date' => $booking->created_at ? $booking->created_at->format('d M Y') : '—',
                    'status' => ucfirst($booking->payment_status),
                ];
            }),
            'total' => $bookings->count(),
        ]);
    }

    public function destroy(User $user)
{
    try {
        $user->bookings()->delete();
        $user->tickets()->delete();

        $user->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'message' => 'Account deleted successfully.'
            ], 200);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Account deleted successfully.');

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to delete account.'
        ], 500);
    }
}
}
