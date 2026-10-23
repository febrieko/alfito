<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');
        $search = $request->query('q');

        $query = User::withCount(['bookings', 'tours']);

        if ($roleFilter && in_array($roleFilter, ['admin', 'agen', 'user'])) {
            $query->where('role', $roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $roleCounts = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'agen' => User::where('role', 'agen')->count(),
            'user' => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'roleFilter', 'search', 'roleCounts'));
    }

    /**
     * Update the user's role.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,agen,user'],
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'Anda tidak dapat menurunkan role akun Anda sendiri!');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role pengguna '{$user->name}' berhasil diubah menjadi {$validated['role']}.");
    }

    /**
     * Remove the user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Pengguna '{$name}' berhasil dihapus.");
    }
}
