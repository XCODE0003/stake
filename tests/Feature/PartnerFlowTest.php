<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class PartnerFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_but_unapproved_user_sees_pending_page(): void
    {
        $user = User::factory()->create(['approved_at' => null]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page->component('PendingApproval'));
    }

    public function test_approved_user_sees_dashboard_with_stats(): void
    {
        $user = User::factory()->create([
            'approved_at' => now(),
            'fixed_payment_amount' => 1500,
            'streams_count' => 12,
            'referrals_count' => 5,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Dashboard')
                    ->where('stats.streamsCount', 12)
                    ->where('stats.referralsCount', 5),
            );
    }

    public function test_approved_user_can_link_wallet(): void
    {
        $user = User::factory()->create(['approved_at' => now()]);

        $this->actingAs($user)
            ->put('/wallet', [
                'wallet_network' => 'USDT_TRC20',
                'wallet_address' => 'TXk9ABCdEfGhIjKlMnOpQrStUvWxYz1234',
            ])
            ->assertRedirect();

        $this->assertSame('USDT_TRC20', $user->fresh()->wallet_network);
    }

    public function test_unapproved_user_cannot_link_wallet(): void
    {
        $user = User::factory()->create(['approved_at' => null]);

        $this->actingAs($user)
            ->put('/wallet', [
                'wallet_network' => 'USDT_TRC20',
                'wallet_address' => 'TXk9ABCdEfGhIjKlMnOpQrStUvWxYz1234',
            ])
            ->assertForbidden();

        $this->assertNull($user->fresh()->wallet_address);
    }

    public function test_non_admin_cannot_access_filament_panel(): void
    {
        $user = User::factory()->create(['approved_at' => now(), 'is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }
}
