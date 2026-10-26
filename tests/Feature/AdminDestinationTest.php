<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminDestinationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_destinations_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $destination = Destination::create([
            'name' => 'Raja Ampat Papua',
            'slug' => 'raja-ampat-papua',
            'region' => 'Papua Barat Daya',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/raja.jpg',
            'description' => 'Kepulauan eksotis di timur Indonesia.',
            'is_featured' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.destinations.index'));

        $response->assertOk();
        $response->assertSee('Raja Ampat Papua');
    }

    public function test_admin_can_create_destination_with_uploaded_photo_and_autoresize(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $largePhoto = UploadedFile::fake()->image('bromo-sunrise.jpg', 2500, 1600);

        $response = $this->actingAs($admin)->post(route('admin.destinations.store'), [
            'name' => 'Gunung Bromo Tengger',
            'region' => 'Jawa Timur',
            'country' => 'Indonesia',
            'thumbnail_file' => $largePhoto,
            'description' => 'Kawasan kaldera gunung berapi dengan panorama sunrise terbaik dunia.',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.destinations.index'));
        $response->assertSessionHas('success');

        $destination = Destination::where('name', 'Gunung Bromo Tengger')->first();
        $this->assertNotNull($destination);
        $this->assertSame('gunung-bromo-tengger', $destination->slug);
        $this->assertStringStartsWith('/storage/destinations/', $destination->thumbnail);

        $storedPath = Str::after($destination->thumbnail, '/storage/');
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_admin_can_update_destination_with_new_uploaded_photo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $destination = Destination::create([
            'name' => 'Nusa Penida',
            'slug' => 'nusa-penida',
            'region' => 'Bali',
            'country' => 'Indonesia',
            'thumbnail' => '/storage/destinations/old-penida.webp',
            'description' => 'Pulau karang nan indah.',
            'is_featured' => false,
        ]);

        $newPhoto = UploadedFile::fake()->image('kelingking-beach.jpg', 1920, 1080);

        $response = $this->actingAs($admin)->put(route('admin.destinations.update', $destination), [
            'name' => 'Nusa Penida Bali Eksotis',
            'region' => 'Bali',
            'country' => 'Indonesia',
            'thumbnail_file' => $newPhoto,
            'description' => 'Deskripsi pulau penida yang telah diperbarui.',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.destinations.index'));
        $destination->refresh();

        $this->assertSame('Nusa Penida Bali Eksotis', $destination->name);
        $this->assertTrue($destination->is_featured);
        $this->assertNotSame('/storage/destinations/old-penida.webp', $destination->thumbnail);
        $this->assertStringStartsWith('/storage/destinations/', $destination->thumbnail);

        $storedPath = Str::after($destination->thumbnail, '/storage/');
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_admin_can_update_destination_retaining_existing_photo_when_file_not_provided(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $destination = Destination::create([
            'name' => 'Kawah Ijen',
            'slug' => 'kawah-ijen',
            'region' => 'Jawa Timur',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/ijen-bluefire.jpg',
            'description' => 'Api biru abadi kawah Ijen.',
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.destinations.update', $destination), [
            'name' => 'Kawah Ijen Blue Fire',
            'region' => 'Banyuwangi, Jawa Timur',
            'country' => 'Indonesia',
            'description' => 'Api biru abadi kawah Ijen dengan pemandangan danau belerang.',
            'is_featured' => '0',
        ]);

        $response->assertRedirect(route('admin.destinations.index'));
        $destination->refresh();

        $this->assertSame('Kawah Ijen Blue Fire', $destination->name);
        $this->assertSame('https://example.com/ijen-bluefire.jpg', $destination->thumbnail);
    }
}
