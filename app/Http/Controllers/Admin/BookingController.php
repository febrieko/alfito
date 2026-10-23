<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');
        $search = $request->query('q');

        $query = Booking::with(['user', 'tour.destination']);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('contact_phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tour', function ($tq) use ($search) {
                        $tq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'paid' => Booking::where('status', 'paid')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'statusFilter', 'search', 'statusCounts'));
    }

    /**
     * Display the specified booking details.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'tour.destination', 'tour.category', 'tour.agen', 'review']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update the status of the booking.
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,paid,completed,cancelled'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $updates = [
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? $booking->admin_note,
        ];

        if ($validated['status'] === 'confirmed' && ! $booking->confirmed_at) {
            $updates['confirmed_at'] = now();
        }

        if ($validated['status'] === 'paid' && ! $booking->paid_at) {
            $updates['paid_at'] = now();
            if (! $booking->confirmed_at) {
                $updates['confirmed_at'] = now();
            }
        }

        $booking->update($updates);

        return back()->with('success', "Status pesanan {$booking->booking_code} berhasil diperbarui menjadi {$validated['status']}.");
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $code = $booking->booking_code;
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', "Pesanan {$code} berhasil dihapus.");
    }
}
