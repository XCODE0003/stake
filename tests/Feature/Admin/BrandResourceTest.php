<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Filament\Resources\Brands\BrandResource;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandResourceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'approved_at' => now()]);
    }

    public function test_admin_can_open_the_sites_list(): void
    {
        Brand::factory()->create(['name' => 'Vodka', 'domain' => 'vodka-partners.com']);

        $this->actingAs($this->admin())
            ->get(BrandResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Vodka');
    }

    public function test_admin_can_open_the_create_site_page(): void
    {
        $this->actingAs($this->admin())
            ->get(BrandResource::getUrl('create'))
            ->assertOk();
    }

    public function test_non_admin_cannot_open_the_sites_list(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'approved_at' => now()]);

        $this->actingAs($user)
            ->get(BrandResource::getUrl('index'))
            ->assertForbidden();
    }
}
