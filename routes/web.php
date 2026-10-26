<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;

// ─── Public Frontend Pages ───────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tours
Route::get('/paket-wisata', [TourController::class, 'index'])->name('tours.index');
Route::get('/paket-wisata/{slug}', [TourController::class, 'show'])->name('tours.show');
Route::post('/paket-wisata/{slug}/review', [TourController::class, 'storeReview'])
    ->name('tours.review')
    ->middleware('auth');

// Destinations
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{slug}', [DestinationController::class, 'show'])->name('destinations.show');

// Bookings
Route::get('/booking/{slug}/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/invoice/{bookingCode}', [BookingController::class, 'show'])->name('booking.show');

// Blog & Travel Guides
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// ─── Authenticated User Pages ────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-pesanan', [BookingController::class, 'myBookings'])->name('booking.my');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $recentBookings = $user->bookings()->with('tour.destination')->latest()->take(5)->get();

        return view('dashboard', compact('user', 'recentBookings'));
    })->middleware('verified')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Administrator Management ──────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Bookings & Transactions
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Tours
    Route::resource('tours', AdminTourController::class)->except(['show']);
    Route::patch('tours/{tour}/toggle-active', [AdminTourController::class, 'toggleActive'])->name('tours.toggle-active');
    Route::patch('tours/{tour}/toggle-featured', [AdminTourController::class, 'toggleFeatured'])->name('tours.toggle-featured');

    // Categories & Sub-categories
    Route::resource('categories', TourCategoryController::class)->except(['show']);
    Route::patch('categories/{category}/toggle', [TourCategoryController::class, 'toggleActive'])->name('categories.toggle');

    // Destinations
    Route::resource('destinations', AdminDestinationController::class)->except(['show']);
    Route::patch('destinations/{destination}/toggle-featured', [AdminDestinationController::class, 'toggleFeatured'])->name('destinations.toggle-featured');

    // Users, Roles & Status
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

    // Review Moderation
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/toggle-approval', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggle-approval');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Blog & Travel Guides
    Route::resource('posts', AdminPostController::class)->except(['show']);
    Route::patch('posts/{post}/toggle-publish', [AdminPostController::class, 'togglePublish'])->name('posts.toggle-publish');

    // Hero Slides
    Route::resource('hero-slides', HeroSlideController::class)->except(['show']);
    Route::patch('hero-slides/{heroSlide}/toggle', [HeroSlideController::class, 'toggleActive'])->name('hero-slides.toggle');

    // Company Profile & Settings
    Route::get('settings', [CompanySettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [CompanySettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
