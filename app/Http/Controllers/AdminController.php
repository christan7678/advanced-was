<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Show register form
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    // Handle register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Admin registered successfully! Please login.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            return redirect()->route('home')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    // Handle logout
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $totalCategories = Category::count();
        $totalEvents = Event::count();
        $totalPurchases = Booking::sum('number_of_seats');
        $totalUsers = User::count();

        $monthEvents = Event::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $weekPurchases = Booking::whereBetween('created_at', [
            $startOfWeek,
            $endOfWeek
        ])->sum('number_of_seats');

        $weekUsers = User::whereBetween('created_at', [
            $startOfWeek,
            $endOfWeek
        ])->count();

        $purchasesByCategory = Category::leftJoin('events', 'categories.id', '=', 'events.category_id')
            ->leftJoin('bookings', 'events.id', '=', 'bookings.event_id')
            ->selectRaw("categories.name, COALESCE(SUM(bookings.number_of_seats), 0) as total_purchases")
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_purchases')
            ->get();

        $maxSeats = $purchasesByCategory->max('total_purchases') ?: 1;

        $paymentCompleted = Booking::where('payment_status', 'completed')->sum('number_of_seats');
        $paymentPending = Booking::where('payment_status', 'pending')->sum('number_of_seats');
        $paymentCancelled = Booking::where('payment_status', 'cancelled')->sum('number_of_seats');

        $completed = $totalPurchases > 0
            ? round(($paymentCompleted / $totalPurchases) * 100, 2)
            : 0;

        $pending = $totalPurchases > 0
            ? round(($paymentPending / $totalPurchases) * 100, 2)
            : 0;

        $cancelled = $totalPurchases > 0
            ? round(($paymentCancelled / $totalPurchases) * 100, 2)
            : 0;

        $pendingEnd = min(100, $completed + $pending);

        $bookingTrendRaw = Booking::selectRaw("DAYOFWEEK(created_at) as day_num, SUM(
                CASE 
                    WHEN bookings.payment_status = 'completed' 
                    THEN bookings.number_of_seats 
                    ELSE 0 
                END) as total_seats")
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupByRaw('DAYOFWEEK(created_at)')
            ->pluck('total_seats', 'day_num');

        $dayMap = [
            2 => 'Mon',
            3 => 'Tue',
            4 => 'Wed',
            5 => 'Thu',
            6 => 'Fri',
            7 => 'Sat',
            1 => 'Sun',
        ];

        $bookingTrend = [];
        foreach ($dayMap as $num => $label) {
            $bookingTrend[] = [
                'label' => $label,
                'total' => $bookingTrendRaw[$num] ?? 0,
            ];
        }

        $maxTrend = max(array_column($bookingTrend, 'total')) ?: 1;

        $recentActivities = Booking::select('bookings.*')
            ->with(['user', 'event'])
            ->latest('updated_at')
            ->take(4)
            ->get();

        $topEvents = Event::with('category')
            ->withSum([
                'bookings as total_revenue' => function ($query) {
                    $query->where('payment_status', 'completed');
                }
            ], 'number_of_seats')
            ->withCount([
                'bookings as completed_count' => function ($query) {
                    $query->where('payment_status', 'completed');
                },
                'bookings as pending_count' => function ($query) {
                    $query->where('payment_status', 'pending');
                },
                'bookings as cancelled_count' => function ($query) {
                    $query->where('payment_status', 'cancelled');
                },
            ])
            ->withSum('bookings as total_purchases', 'number_of_seats')
            ->orderByDesc('total_purchases')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalEvents',
            'totalPurchases',
            'totalUsers',
            'monthEvents',
            'weekPurchases',
            'weekUsers',
            'purchasesByCategory',
            'maxSeats',
            'paymentCompleted',
            'paymentPending',
            'paymentCancelled',
            'completed',
            'pending',
            'cancelled',
            'pendingEnd',
            'bookingTrend',
            'maxTrend',
            'recentActivities',
            'topEvents'
        ));
    }
}