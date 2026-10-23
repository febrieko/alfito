<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourTest extends TestCase
{
    use RefreshDatabase;

    private User $agen;

    private Destination $destination;

    private Tour $tour;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agen = User::factory()->create([
            'role' => 'agen',
        ]);

        $this->destination = Destination::create([
            'name' => 'Gunung Bromo',
            'slug' => 'gunung-bromo',
            'region' => 'Jawa Timur',
            'description' => 'Keindahan kawah Bromo',
            'is_featured' => true,
        ]);

        $this->tour = Tour::create([
            'user_id' => $this->agen->id,
            'destination_id' => $this->destination->id,
            'title' => 'Open Trip Bromo Sunrise',
            'slug' => 'open-trip-bromo-sunrise',
            'type' => 'open_trip',
            'duration_days' => 1,
            'duration_nights' => 0,
            'price' => 350000,
            'description' => 'Wisata Bromo Midnight',
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    public function test_homepage_shows_featured_tours(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ALFITO');
        $response->assertSee('Open Trip Bromo Sunrise');
        $response->assertSee('Gunung Bromo');
    }

    public function test_tours_index_lists_active_tours(): void
    {
        $response = $this->get('/paket-wisata');

        $response->assertStatus(200);
        $response->assertSee('Open Trip Bromo Sunrise');
    }

    public function test_tour_detail_page_loads_successfully(): void
    {
        $response = $this->get('/paket-wisata/'.$this->tour->slug);

        $response->assertStatus(200);
        $response->assertSee('Open Trip Bromo Sunrise');
        $response->assertSee('Gunung Bromo');
        $response->assertSee('350.000');
    }

    public function test_destinations_page_loads_successfully(): void
    {
        $response = $this->get('/destinasi');

        $response->assertStatus(200);
        $response->assertSee('Gunung Bromo');
    }

    public function test_customer_can_create_a_booking(): void
    {
        $response = $this->post('/booking', [
            'tour_id' => $this->tour->id,
            'travel_date' => now()->addDays(5)->format('Y-m-d'),
            'pax_count' => 2,
            'name' => 'John Traveler',
            'email' => 'john@traveler.test',
            'contact_phone' => '081234567899',
            'special_request' => 'Jemput di stasiun',
        ]);

        $this->assertDatabaseHas('bookings', [
            'tour_id' => $this->tour->id,
            'pax_count' => 2,
            'total_price' => 700000,
            'contact_phone' => '081234567899',
        ]);

        $response->assertRedirect();
    }
}
