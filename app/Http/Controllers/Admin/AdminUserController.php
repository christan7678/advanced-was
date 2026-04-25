<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,super_admin']);
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $usersQuery = User::where('role', 'user');

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        $users = $usersQuery->latest()->get();
        $admins = collect([]);

        return view('admin.users.index', compact('users', 'admins', 'q'));
    }

    public function searchUsers(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $users = User::where('role', 'user')
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
                    'phone_number' => $user->phone_number,
                    'bookings_count' => $user->bookings()->count(),
                    'created_at' => $user->created_at
                        ? $user->created_at->format('d M Y')
                        : '—',
                ];
            });

        return response()->json(['users' => $users]);
    }

    public function show(User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        $user->load(['bookings.event.category']);

        return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => [
                    'required',
                    'regex:/^(\+?6?01)[0-9]{8,9}$/'
                ],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->mixedCase()->numbers()->symbols(),
                ],
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
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
        if ($user->role !== 'user') {
            abort(404);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => [
                    'required',
                    'regex:/^(\+?6?01)[0-9]{8,9}$/'
                ],
                'password' => [
                    'nullable',
                    'confirmed',
                    Password::min(8)->mixedCase()->numbers()->symbols(),
                ],
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

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
        if ($user->role !== 'user') {
            abort(404);
        }

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
        if ($user->role !== 'user') {
            abort(404);
        }

        try {
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