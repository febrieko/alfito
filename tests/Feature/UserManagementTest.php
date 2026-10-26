<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_user_management(): void
    {
        $member = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($member)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'John Traveler', 'role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee('Tambah Pengguna Baru');
        $response->assertSee('John Traveler');
    }

    public function test_admin_can_view_create_user_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.create'));

        $response->assertOk();
        $response->assertSee('Tambah Pengguna Baru');
        $response->assertSee('Simpan Pengguna Baru');
    }

    public function test_admin_can_store_new_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Mitra Agen Bromo',
            'email' => 'agen.bromo@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'role' => 'agen',
            'phone' => '081299998888',
            'bio' => 'Agen resmi area Bromo',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Mitra Agen Bromo',
            'email' => 'agen.bromo@example.com',
            'role' => 'agen',
            'is_active' => true,
        ]);

        $created = User::where('email', 'agen.bromo@example.com')->first();
        $this->assertTrue(Hash::check('secret12345', $created->password));
    }

    public function test_admin_can_view_edit_user_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'Alice Member']);

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user));

        $response->assertOk();
        $response->assertSee('Edit Pengguna');
        $response->assertSee('Alice Member');
    }

    public function test_admin_can_update_user_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'user',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'New Name Updated',
            'email' => 'new@example.com',
            'role' => 'agen',
            'phone' => '081122334455',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('New Name Updated', $user->name);
        $this->assertSame('new@example.com', $user->email);
        $this->assertSame('agen', $user->role);
        $this->assertTrue($user->isActive());
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['is_active' => true]);

        // Toggle to inactive
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-active', $user));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertFalse($user->isActive());

        // Toggle back to active
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-active', $user));
        $response->assertRedirect();

        $user->refresh();
        $this->assertTrue($user->isActive());
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'banned@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'banned@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_cannot_deactivate_or_demote_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        // Cannot toggle active self
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-active', $admin));
        $response->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->isActive());

        // Cannot demote self
        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
            'is_active' => '1',
        ]);
        $response->assertSessionHas('error');
        $this->assertSame('admin', $admin->fresh()->role);

        // Cannot delete self
        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));
        $response->assertSessionHas('error');
        $this->assertNotNull($admin->fresh());
    }

    public function test_admin_can_create_user_with_avatar(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $avatarFile = UploadedFile::fake()->image('agent-avatar.jpg', 1800, 1800);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Agent With Avatar',
            'email' => 'agent.avatar@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'role' => 'agen',
            'avatar_file' => $avatarFile,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $created = User::where('email', 'agent.avatar@example.com')->first();
        $this->assertNotNull($created);
        $this->assertNotNull($created->avatar);
        $this->assertStringStartsWith('/storage/avatars/', $created->avatar);
    }
}
