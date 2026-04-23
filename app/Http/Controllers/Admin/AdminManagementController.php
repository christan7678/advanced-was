<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-admins']);
    }

    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
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

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return response()->json([
            'message' => 'Admin account created successfully.',
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone_number' => $admin->phone_number,
                'role' => $admin->role,
                'created_at' => $admin->created_at ? $admin->created_at->format('d M Y') : '—',
            ]
        ], 201);
    }

    public function show(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'super_admin'])) {
            abort(404);
        }

        return view('admin.admins.show', compact('admin'));
    }

    public function searchAdmins(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get()
            ->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'phone_number' => $admin->phone_number,
                    'created_at' => $admin->created_at
                        ? $admin->created_at->format('d M Y')
                        : '—',
                ];
            });

        return response()->json(['admins' => $admins]);
    }

    public function update(Request $request, User $admin)
    {
        if (!in_array($admin->role, ['admin', 'super_admin'])) {
            return response()->json([
                'message' => 'Admin account not found.'
            ], 404);
        }

        if ($admin->role === 'super_admin') {
            return response()->json([
                'message' => 'Cannot edit super admin.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $admin->id,
            'phone_number' => 'nullable|string|max:15',
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $updateData = [];

        if ($request->filled('name')) {
            $updateData['name'] = $validated['name'];
        }

        if ($request->filled('email')) {
            $updateData['email'] = $validated['email'];
        }

        if ($request->filled('phone_number')) {
            $updateData['phone_number'] = $validated['phone_number'];
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return response()->json([
            'message' => 'Admin account updated successfully.'
        ], 200);
    }

    public function destroy(User $admin)
    {
        if (!in_array($admin->role, ['admin', 'super_admin'])) {
            return response()->json([
                'message' => 'Admin account not found.'
            ], 404);
        }

        if ($admin->role === 'super_admin') {
            return response()->json([
                'message' => 'Cannot delete super admin.'
            ], 403);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Admin account deleted successfully.'
        ], 200);
    }
}