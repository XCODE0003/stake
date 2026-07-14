<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Filament\Resources\Users\UserResource;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'approved_at' => now()]);
    }

    public function test_the_users_list_shows_the_domain_each_affiliate_registered_from(): void
    {
        $brand = Brand::factory()->create(['name' => 'Vodka', 'domain' => 'vodka-partners.com']);

        User::factory()->create([
            'name' => 'streamer',
            'brand_id' => $brand->id,
            'signup_domain' => 'vodka-partners.com',
        ]);

        $this->actingAs($this->admin())
            ->get(UserResource::getUrl('index'))
            ->assertOk()
            ->assertSee('vodka-partners.com');
    }

    public function test_the_users_list_shows_the_domain_even_when_it_matches_no_site(): void
    {
        User::factory()->create([
            'name' => 'streamer',
            'brand_id' => null,
            'signup_domain' => 'unknown-site.com',
        ]);

        $this->actingAs($this->admin())
            ->get(UserResource::getUrl('index'))
            ->assertOk()
            ->assertSee('unknown-site.com');
    }

    public function test_the_edit_page_shows_the_signup_domain(): void
    {
        $user = User::factory()->create(['signup_domain' => 'vodka-partners.com']);

        $this->actingAs($this->admin())
            ->get(UserResource::getUrl('edit', ['record' => $user]))
            ->assertOk()
            ->assertSee('vodka-partners.com');
    }

    public function test_non_admin_cannot_open_the_users_list(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(UserResource::getUrl('index'))
            ->assertForbidden();
    }
}
