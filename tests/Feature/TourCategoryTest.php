<?php

namespace Tests\Feature;

use App\Models\TourCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->regularUser = User::factory()->create([
            'role' => 'user',
        ]);
    }

    public function test_guest_cannot_access_category_admin_pages(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_category_admin_pages(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_categories_index(): void
    {
        $gathering = TourCategory::create([
            'name' => 'Gathering Kantor',
            'slug' => 'gathering-kantor',
            'icon' => '🏢',
            'is_active' => true,
        ]);

        TourCategory::create([
            'parent_id' => $gathering->id,
            'name' => 'Outbound & Team Building',
            'slug' => 'outbound-team-building',
            'icon' => '🎯',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Gathering Kantor');
        $response->assertSee('Outbound & Team Building');
    }

    public function test_admin_can_create_parent_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Gathering Kantor',
            'icon' => '🏢',
            'description' => 'Paket wisata korporat',
            'color' => 'sky',
            'order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tour_categories', [
            'name' => 'Gathering Kantor',
            'parent_id' => null,
            'slug' => 'gathering-kantor',
            'icon' => '🏢',
        ]);
    }

    public function test_admin_can_create_sub_category(): void
    {
        $parent = TourCategory::create([
            'name' => 'Gathering Kantor',
            'slug' => 'gathering-kantor',
            'icon' => '🏢',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'parent_id' => $parent->id,
            'name' => 'Outbound & Team Building',
            'icon' => '🎯',
            'description' => 'Aktivitas seru outbound',
            'color' => 'emerald',
            'order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tour_categories', [
            'parent_id' => $parent->id,
            'name' => 'Outbound & Team Building',
            'slug' => 'outbound-team-building',
            'icon' => '🎯',
        ]);
    }

    public function test_admin_can_update_sub_category(): void
    {
        $parent = TourCategory::create([
            'name' => 'Gathering Kantor',
            'slug' => 'gathering-kantor',
            'icon' => '🏢',
        ]);

        $sub = TourCategory::create([
            'parent_id' => $parent->id,
            'name' => 'Raker Lama',
            'slug' => 'raker-lama',
            'icon' => '📊',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $sub), [
            'parent_id' => $parent->id,
            'name' => 'Rapat Kerja (Raker) & Workshop',
            'slug' => 'raker-workshop',
            'icon' => '📈',
            'order' => 2,
            'is_active' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tour_categories', [
            'id' => $sub->id,
            'name' => 'Rapat Kerja (Raker) & Workshop',
            'slug' => 'raker-workshop',
            'icon' => '📈',
        ]);
    }

    public function test_admin_can_toggle_category_status(): void
    {
        $category = TourCategory::create([
            'name' => 'Family Gathering',
            'slug' => 'family-gathering',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.categories.toggle', $category));

        $response->assertRedirect();
        $this->assertDatabaseHas('tour_categories', [
            'id' => $category->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_sub_category(): void
    {
        $parent = TourCategory::create([
            'name' => 'Gathering Kantor',
            'slug' => 'gathering-kantor',
        ]);

        $sub = TourCategory::create([
            'parent_id' => $parent->id,
            'name' => 'Gala Dinner',
            'slug' => 'gala-dinner',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $sub));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tour_categories', [
            'id' => $sub->id,
        ]);
    }
}
