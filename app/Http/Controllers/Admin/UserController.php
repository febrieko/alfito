<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        public ImageService $imageService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');
        $statusFilter = $request->query('status');
        $search = $request->query('q');

        $query = User::withCount(['bookings', 'tours']);

        if ($roleFilter && in_array($roleFilter, ['admin', 'agen', 'user'])) {
            $query->where('role', $roleFilter);
        }

        if ($statusFilter !== null && in_array($statusFilter, ['active', 'inactive'])) {
            $query->where('is_active', $statusFilter === 'active');
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
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'roleFilter', 'statusFilter', 'search', 'roleCounts'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,agen,user'],
            'phone' => ['nullable', 'string', 'max:25'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:12288'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $avatar = null;
        if ($request->hasFile('avatar_file')) {
            $avatar = $this->imageService->resizeAndStore(
                $request->file('avatar_file'),
                'avatars',
                500,
                500,
                85
            );
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'avatar' => $avatar,
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna '{$user->name}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,agen,user'],
            'phone' => ['nullable', 'string', 'max:25'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:12288'],
            'remove_avatar' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Safety rules for currently authenticated admin
        if ($user->id === auth()->id()) {
            if ($validated['role'] !== 'admin') {
                return back()->withInput()->with('error', 'Anda tidak dapat menurunkan role akun Anda sendiri!');
            }

            if (! $request->boolean('is_active', true)) {
                return back()->withInput()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->boolean('remove_avatar')) {
            $this->deleteOldAvatar($user->avatar);
            $data['avatar'] = null;
        } elseif ($request->hasFile('avatar_file')) {
            $this->deleteOldAvatar($user->avatar);
            $data['avatar'] = $this->imageService->resizeAndStore(
                $request->file('avatar_file'),
                'avatars',
                500,
                500,
                85
            );
        }

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Toggle the user's active/disabled status.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }

        $newStatus = ! $user->isActive();
        $user->update(['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun pengguna '{$user->name}' berhasil {$statusLabel}.");
    }

    /**
     * Update the user's role directly.
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
        $this->deleteOldAvatar($user->avatar);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Pengguna '{$name}' berhasil dihapus.");
    }

    /**
     * Delete an existing avatar file from public storage.
     */
    private function deleteOldAvatar(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http')) {
            return;
        }

        $relativePath = Str::after($path, '/storage/');
        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
