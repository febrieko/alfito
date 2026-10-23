<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Show booking checkout / confirmation form.
     */
    public function create(Request $request, string $slug): View
    {
        $tour = Tour::with('destination')->where('slug', $slug)->firstOrFail();
        $pax = (int) $request->input('pax', 1);
        $travelDate = $request->input('date', now()->addDays(3)->format('Y-m-d'));

        return view('pages.booking.create', compact('tour', 'pax', 'travelDate'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tour_id' => ['required', 'exists:tours,id'],
            'travel_date' => ['required', 'date', 'after:today'],
            'pax_count' => ['required', 'integer', 'min:1', 'max:50'],
            'contact_phone' => ['required', 'string', 'max:25'],
            'special_request' => ['nullable', 'string', 'max:500'],
            'name' => ['required_without:user_id', 'nullable', 'string', 'max:255'],
            'email' => ['required_without:user_id', 'nullable', 'email', 'max:255'],
        ]);

        $tour = Tour::findOrFail($validated['tour_id']);

        // Determine user
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            // Find or create customer account
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'phone' => $validated['contact_phone'],
                    'password' => Hash::make(Str::random(12)),
                    'role' => 'user',
                ]
            );
            Auth::login($user);
        }

        $unitPrice = ($tour->type === 'private_trip' && $tour->price_private)
            ? ($tour->price_private / max(1, $validated['pax_count']))
            : $tour->price;

        $totalPrice = $unitPrice * $validated['pax_count'];

        $booking = Booking::create([
            'user_id' => $user->id,
            'tour_id' => $tour->id,
            'travel_date' => $validated['travel_date'],
            'pax_count' => $validated['pax_count'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'contact_phone' => $validated['contact_phone'],
            'special_request' => $validated['special_request'] ?? null,
        ]);

        return redirect()->route('booking.show', $booking->booking_code)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan hubungi Customer Service kami via WhatsApp untuk konfirmasi instan.');
    }

    /**
     * Display the specified booking / invoice receipt.
     */
    public function show(string $bookingCode): View
    {
        $booking = Booking::with(['tour.destination', 'user'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        return view('pages.booking.show', compact('booking'));
    }

    /**
     * User's personal booking list in dashboard.
     */
    public function myBookings(): View
    {
        $bookings = Booking::with(['tour.destination'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.booking.index', compact('bookings'));
    }
}
