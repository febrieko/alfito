<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_dashboard_with_company_branding(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        CompanySetting::createDefault();
        $setting = CompanySetting::first();
        $setting->update([
            'logo_url' => 'https://example.com/alfito-logo.png',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('https://example.com/alfito-logo.png');
        $response->assertSee('rel="icon" href="https://example.com/alfito-logo.png"', false);
    }

    public function test_logo_is_used_as_favicon_on_frontend_and_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        CompanySetting::createDefault();
        $setting = CompanySetting::first();
        $setting->update([
            'logo_url' => 'https://example.com/brand-logo.png',
        ]);

        $frontendResponse = $this->get(route('home'));
        $frontendResponse->assertStatus(200);
        $frontendResponse->assertSee('rel="icon" href="https://example.com/brand-logo.png"', false);

        $adminResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('https://example.com/brand-logo.png');
        $adminResponse->assertSee('rel="icon" href="https://example.com/brand-logo.png"', false);
    }
}
