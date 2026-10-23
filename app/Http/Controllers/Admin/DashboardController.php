<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Post;
use App\Models\Review;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main admin dashboard overview.
     */
    public function index(): View
    {
        $stats = [
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'paid', 'completed'])->sum('total_price'),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::whereIn('status', ['confirmed', 'paid'])->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'total_tours' => Tour::count(),
            'active_tours' => Tour::where('is_active', true)->count(),
            'total_destinations' => Destination::count(),
            'total_categories' => TourCategory::count(),
            'total_users' => User::count(),
            'total_agents' => User::where('role', 'agen')->count(),
            'total_posts' => Post::count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
        ];

        $recentBookings = Booking::with(['user', 'tour'])
            ->latest()
            ->take(8)
            ->get();

        $pendingReviews = Review::with(['user', 'tour'])
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        $topTours = Tour::with(['destination', 'category'])
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingReviews', 'topTours'));
    }
}
