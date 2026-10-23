<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CompanySetting;
use App\Models\Destination;
use App\Models\Review;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminPanelTest extends TestCase
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

    public function test_admin_can_access_dashboard_overview(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Kendali Administrator');
        $response->assertSee('Total Omset Sukses');
    }

    public function test_admin_can_manage_bookings_and_update_status(): void
    {
        $destination = Destination::create([
            'name' => 'Labuan Bajo',
            'slug' => 'labuan-bajo',
            'region' => 'NTT',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/thumb.jpg',
            'description' => 'Eksotisme pulau komodo',
        ]);

        $tour = Tour::create([
            'user_id' => $this->admin->id,
            'destination_id' => $destination->id,
            'title' => 'Sailing Phinisi Komodo',
            'slug' => 'sailing-phinisi-komodo',
            'type' => 'open_trip',
            'duration_days' => 3,
            'duration_nights' => 2,
            'price' => 2500000,
            'min_pax' => 1,
            'max_pax' => 10,
            'thumbnail' => 'https://example.com/tour.jpg',
            'description' => 'Tour phinisi mewah',
        ]);

        $booking = Booking::create([
            'booking_code' => 'WK-20261023-001',
            'user_id' => $this->regularUser->id,
            'tour_id' => $tour->id,
            'travel_date' => now()->addDays(7)->toDateString(),
            'pax_count' => 2,
            'total_price' => 5000000,
            'status' => 'pending',
            'contact_phone' => '081234567890',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index'));
        $response->assertStatus(200);
        $response->assertSee($booking->booking_code);

        // Update status to paid
        $updateResponse = $this->actingAs($this->admin)->patch(route('admin.bookings.status', $booking), [
            'status' => 'paid',
            'admin_note' => 'Pembayaran BCA terverifikasi.',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'paid',
            'admin_note' => 'Pembayaran BCA terverifikasi.',
        ]);
    }

    public function test_admin_can_create_and_toggle_tour(): void
    {
        $destination = Destination::create([
            'name' => 'Bromo Tengger',
            'slug' => 'bromo-tengger',
            'region' => 'Jawa Timur',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/bromo.jpg',
            'description' => 'Golden sunrise Bromo',
        ]);

        $category = TourCategory::create([
            'name' => 'Gathering Kantor',
            'slug' => 'gathering-kantor',
            'icon' => '🏢',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tours.store'), [
            'title' => 'Gathering Kantor Bromo Sunrise Midnight',
            'destination_id' => $destination->id,
            'category_id' => $category->id,
            'user_id' => $this->admin->id,
            'type' => 'private_trip',
            'duration_days' => 2,
            'duration_nights' => 1,
            'price' => 1250000,
            'min_pax' => 5,
            'max_pax' => 50,
            'thumbnail' => 'https://example.com/thumb.jpg',
            'description' => 'Paket gathering kantor seru melihat sunrise',
            'itinerary' => [
                [
                    'day' => 1,
                    'title' => 'Penjemputan & Menuju Bromo',
                    'activities' => "Penjemputan di Malang\nMenuju hotel di Tosari\nIstirahat & briefing midnight",
                ],
                [
                    'day' => 2,
                    'title' => 'Sunrise Penanjakan & Pasir Berbisik',
                    'activities' => "03.00: Naik Jeep 4x4\n05.00: Golden Sunrise\n08.00: Kawah Bromo & Pasir Berbisik",
                ],
            ],
            'includes' => "Jeep 4x4 Bromo\nTiket Masuk TNBTS\nHotel 1 Malam\nDriver & BBM",
            'excludes' => "Tiket Kereta / Pesawat\nPengeluaran Pribadi\nSewa Jaket / Kuda",
            'is_active' => 1,
            'is_featured' => 1,
        ]);

        $response->assertRedirect(route('admin.tours.index'));
        $this->assertDatabaseHas('tours', [
            'title' => 'Gathering Kantor Bromo Sunrise Midnight',
            'is_featured' => true,
        ]);

        $tour = Tour::where('title', 'Gathering Kantor Bromo Sunrise Midnight')->first();
        $this->assertCount(2, $tour->itinerary);
        $this->assertEquals('Penjemputan & Menuju Bromo', $tour->itinerary[0]['title']);
        $this->assertCount(3, $tour->itinerary[0]['activities']);
        $this->assertCount(4, $tour->includes);
        $this->assertContains('Jeep 4x4 Bromo', $tour->includes);
        $this->assertCount(3, $tour->excludes);
        $this->assertContains('Sewa Jaket / Kuda', $tour->excludes);

        $this->actingAs($this->admin)->patch(route('admin.tours.toggle-active', $tour));
        $this->assertDatabaseHas('tours', [
            'id' => $tour->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_upload_multiple_images_with_auto_resize(): void
    {
        Storage::fake('public');

        $destination = Destination::create([
            'name' => 'Raja Ampat',
            'slug' => 'raja-ampat',
            'region' => 'Papua Barat',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/raja.jpg',
            'description' => 'Surga bawah laut dunia',
        ]);

        // Create an oversized image (3200x2400 px, 3MB+)
        $largePhoto1 = UploadedFile::fake()->image('oversized_landscape.jpg', 3200, 2400);
        $largePhoto2 = UploadedFile::fake()->image('oversized_portrait.jpg', 1800, 2600);

        $response = $this->actingAs($this->admin)->post(route('admin.tours.store'), [
            'title' => 'Eksotisme Misool Raja Ampat 4H3M',
            'destination_id' => $destination->id,
            'user_id' => $this->admin->id,
            'type' => 'open_trip',
            'duration_days' => 4,
            'duration_nights' => 3,
            'price' => 7500000,
            'min_pax' => 2,
            'max_pax' => 12,
            'description' => 'Jelajahi keindahan gugusan karst dan laguna Misool dengan dokumentasi drone.',
            'gallery_files' => [$largePhoto1, $largePhoto2],
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.tours.index'));

        $tour = Tour::where('title', 'Eksotisme Misool Raja Ampat 4H3M')->firstOrFail();
        $this->assertNotNull($tour->thumbnail);
        $this->assertStringStartsWith('/storage/tours/', $tour->thumbnail);
        $this->assertIsArray($tour->images);
        $this->assertCount(2, $tour->images);

        // Verify storage file exists on disk
        $relativePath = Str::after($tour->thumbnail, '/storage/');
        Storage::disk('public')->assertExists($relativePath);

        // Check resized dimensions on the stored image
        $fullPath = Storage::disk('public')->path($relativePath);
        $imageInfo = getimagesize($fullPath);
        $this->assertNotFalse($imageInfo);
        [$storedWidth, $storedHeight] = $imageInfo;

        // Auto-resize should keep max dimensions <= 1600px width and <= 1200px height
        $this->assertLessThanOrEqual(1600, $storedWidth);
        $this->assertLessThanOrEqual(1200, $storedHeight);
    }

    public function test_admin_can_update_tour_gallery_and_thumbnail(): void
    {
        Storage::fake('public');

        $destination = Destination::create([
            'name' => 'Belitung',
            'slug' => 'belitung',
            'region' => 'Bangka Belitung',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/belitung.jpg',
            'description' => 'Negeri Laskar Pelangi',
        ]);

        $tour = Tour::create([
            'user_id' => $this->admin->id,
            'destination_id' => $destination->id,
            'title' => 'Island Hopping Belitung 3H2M',
            'slug' => 'island-hopping-belitung-3h2m',
            'type' => 'open_trip',
            'duration_days' => 3,
            'duration_nights' => 2,
            'price' => 1950000,
            'min_pax' => 2,
            'max_pax' => 20,
            'thumbnail' => 'https://example.com/old_thumb.jpg',
            'images' => ['https://example.com/old_thumb.jpg', 'https://example.com/old_gallery.jpg'],
            'description' => 'Tour pulau batu granit',
        ]);

        $newUpload = UploadedFile::fake()->image('new_granite.jpg', 2400, 1600);

        $response = $this->actingAs($this->admin)->put(route('admin.tours.update', $tour), [
            'title' => 'Island Hopping Belitung 3H2M Updated',
            'destination_id' => $destination->id,
            'user_id' => $this->admin->id,
            'type' => 'open_trip',
            'duration_days' => 3,
            'duration_nights' => 2,
            'price' => 2100000,
            'min_pax' => 2,
            'max_pax' => 20,
            'description' => 'Tour pulau batu granit diperbarui',
            'existing_images' => ['https://example.com/old_thumb.jpg'], // Kept only one existing
            'gallery_files' => [$newUpload],
            'selected_thumbnail' => 'https://example.com/old_thumb.jpg',
        ]);

        $response->assertRedirect(route('admin.tours.index'));

        $tour->refresh();
        $this->assertEquals('Island Hopping Belitung 3H2M Updated', $tour->title);
        $this->assertCount(2, $tour->images);
        $this->assertContains('https://example.com/old_thumb.jpg', $tour->images);
        // Second image should be the newly resized upload
        $newStoredImage = collect($tour->images)->first(fn ($img) => str_starts_with($img, '/storage/tours/'));
        $this->assertNotNull($newStoredImage);
    }

    public function test_admin_can_update_user_role(): void
    {
        $targetUser = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.role', $targetUser), [
            'role' => 'agen',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'role' => 'agen',
        ]);
    }

    public function test_admin_can_toggle_review_approval(): void
    {
        $destination = Destination::create([
            'name' => 'Nusa Penida',
            'slug' => 'nusa-penida',
            'region' => 'Bali',
            'country' => 'Indonesia',
            'thumbnail' => 'https://example.com/bali.jpg',
            'description' => 'Pulau dewata',
        ]);

        $tour = Tour::create([
            'user_id' => $this->admin->id,
            'destination_id' => $destination->id,
            'title' => 'Trip Nusa Penida 1 Hari',
            'slug' => 'trip-nusa-penida',
            'type' => 'open_trip',
            'duration_days' => 1,
            'duration_nights' => 0,
            'price' => 500000,
            'min_pax' => 1,
            'max_pax' => 10,
            'thumbnail' => 'https://example.com/bali.jpg',
            'description' => 'Tour nusa penida',
        ]);

        $review = Review::create([
            'user_id' => $this->regularUser->id,
            'tour_id' => $tour->id,
            'rating' => 5,
            'comment' => 'Pemandunya sangat ramah!',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.reviews.toggle-approval', $review));

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'is_approved' => true,
        ]);
    }

    public function test_admin_can_create_and_publish_post(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Panduan Tips Liburan Nyaman Bersama Rombongan Kantor',
            'category' => 'Gathering & Outbound',
            'thumbnail' => 'https://example.com/blog.jpg',
            'excerpt' => 'Tips memilih paket gathering yang tepat untuk instansi.',
            'content' => 'Isi artikel lengkap mengenai kiat sukses menyelenggarakan gathering kantor.',
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', [
            'title' => 'Panduan Tips Liburan Nyaman Bersama Rombongan Kantor',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_access_and_update_company_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.edit'));
        $response->assertStatus(200);
        $response->assertSee('Profil Perusahaan & Kontak');
        $response->assertSee('Nomor WhatsApp Customer Service');

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'company_name' => 'PT ALFITO WISATA BAHARI',
            'short_name' => 'ALFITO',
            'tagline' => 'Spesialis Gathering & Tour Nusantara',
            'description' => 'Tour operator terpercaya se-Indonesia dengan pengalaman lebih dari 10 tahun.',
            'whatsapp_number' => '081299988877',
            'phone' => '0812-9998-8877',
            'email' => 'official@alfito.com',
            'address' => 'Jl. Danau Toba Raya No. 88, Malang, Jawa Timur',
            'operational_hours' => '24 Jam Nonstop',
            'license_info' => 'Berlisensi Resmi ASITA & HPI 2026',
            'threads_url' => 'https://threads.net/@alfito.travel',
            'bank_accounts' => [
                ['bank' => 'BCA', 'account_number' => '888-123-4567', 'account_name' => 'PT ALFITO WISATA BAHARI'],
            ],
        ]);

        $updateResponse->assertRedirect(route('admin.settings.edit'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('company_settings', [
            'company_name' => 'PT ALFITO WISATA BAHARI',
            'tagline' => 'Spesialis Gathering & Tour Nusantara',
            'whatsapp_number' => '081299988877',
            'threads_url' => 'https://threads.net/@alfito.travel',
            'email' => 'official@alfito.com',
        ]);

        $setting = CompanySetting::get();
        $this->assertEquals('6281299988877', $setting->clean_whatsapp);
        $this->assertEquals('https://threads.net/@alfito.travel', $setting->threads_url);
        $this->assertEquals('Spesialis Gathering & Tour Nusantara', $setting->tagline);

        // Verify that public home page reflects the updated company name, tagline, and WhatsApp number
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('6281299988877');
        $homeResponse->assertSee('PT ALFITO WISATA BAHARI');
        $homeResponse->assertSee('Spesialis Gathering & Tour Nusantara');
        $homeResponse->assertSee('https://threads.net/@alfito.travel');
    }
}
