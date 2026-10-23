<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSlideTest extends TestCase
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

    public function test_guest_cannot_access_hero_slide_admin_pages(): void
    {
        $response = $this->get(route('admin.hero-slides.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_hero_slide_admin_pages(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.hero-slides.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_hero_slides_index(): void
    {
        HeroSlide::create([
            'title' => 'Eksplorasi Labuan Bajo Phinisi',
            'image' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.hero-slides.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Slider Hero');
        $response->assertSee('Eksplorasi Labuan Bajo Phinisi');
    }

    public function test_admin_can_create_hero_slide_with_url(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.hero-slides.store'), [
            'title' => 'Keindahan Raja Ampat',
            'subtitle' => 'Surga bawah laut terindah',
            'badge' => 'The Best Paradise',
            'image_type' => 'url',
            'image_url' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf',
            'order' => 2,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.hero-slides.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hero_slides', [
            'title' => 'Keindahan Raja Ampat',
            'image' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf',
            'order' => 2,
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_create_hero_slide_with_file_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('hero-slide.jpg', 1920, 1080);

        $response = $this->actingAs($this->admin)->post(route('admin.hero-slides.store'), [
            'title' => 'Gunung Bromo Golden Sunrise',
            'image_type' => 'file',
            'image_file' => $file,
            'order' => 3,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.hero-slides.index'));

        $slide = HeroSlide::where('title', 'Gunung Bromo Golden Sunrise')->first();
        $this->assertNotNull($slide);
        Storage::disk('public')->assertExists($slide->image);
    }

    public function test_admin_can_toggle_hero_slide_active_status(): void
    {
        $slide = HeroSlide::create([
            'title' => 'Toggle Test Slide',
            'image' => 'https://example.com/test.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.hero-slides.toggle', $slide));

        $response->assertRedirect(route('admin.hero-slides.index'));
        $this->assertFalse($slide->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($this->admin)->patch(route('admin.hero-slides.toggle', $slide));
        $this->assertTrue($slide->fresh()->is_active);
    }

    public function test_admin_can_delete_hero_slide(): void
    {
        $slide = HeroSlide::create([
            'title' => 'Delete Slide Test',
            'image' => 'https://example.com/del.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.hero-slides.destroy', $slide));

        $response->assertRedirect(route('admin.hero-slides.index'));
        $this->assertDatabaseMissing('hero_slides', ['id' => $slide->id]);
    }

    public function test_homepage_renders_active_hero_slides(): void
    {
        HeroSlide::create([
            'title' => 'Slide Eksplorasi Utama Beranda',
            'image' => 'https://example.com/hero.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Slide Eksplorasi Utama Beranda');
    }
}
