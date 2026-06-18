<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use App\Support\EmailVerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_token_and_unverified_account(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'streamer',
            'email' => 'streamer@example.com',
            'password' => 'password',
            'referral_code' => 'FRIEND10',
            'terms' => true,
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['token', 'data' => ['id', 'email', 'email_verified', 'approved']])
            ->assertJsonPath('data.email_verified', false)
            ->assertJsonPath('data.approved', false);

        $this->assertDatabaseHas('users', [
            'email' => 'streamer@example.com',
            'referral_code' => 'FRIEND10',
        ]);
    }

    public function test_register_requires_terms(): void
    {
        $this->postJson('/api/register', [
            'name' => 'streamer',
            'email' => 'streamer@example.com',
            'password' => 'password',
        ])->assertStatus(422)->assertJsonValidationErrors('terms');
    }

    public function test_login_returns_token(): void
    {
        User::factory()->create([
            'email' => 'partner@example.com',
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email' => 'partner@example.com',
            'password' => 'password',
        ])->assertOk()->assertJsonStructure(['token', 'data' => ['id', 'email']]);
    }

    public function test_login_rejects_bad_credentials(): void
    {
        User::factory()->create([
            'email' => 'partner@example.com',
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email' => 'partner@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_user_can_verify_email_with_code(): void
    {
        $user = User::factory()->unverified()->create();
        $code = app(EmailVerificationCode::class)->generate($user);

        Sanctum::actingAs($user);

        $this->postJson('/api/email/verify', ['code' => $code])
            ->assertOk()
            ->assertJsonPath('data.email_verified', true);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_verify_rejects_wrong_code(): void
    {
        $user = User::factory()->unverified()->create();
        app(EmailVerificationCode::class)->generate($user);

        Sanctum::actingAs($user);

        $this->postJson('/api/email/verify', ['code' => '000000'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('code');
    }

    public function test_approved_user_can_link_wallet(): void
    {
        $user = User::factory()->create(['approved_at' => now()]);

        Sanctum::actingAs($user);

        $this->putJson('/api/wallet', [
            'wallet_network' => 'USDT_TRC20',
            'wallet_address' => 'TXk9ABCdEfGhIjKlMnOpQrStUvWxYz1234',
        ])->assertOk()->assertJsonPath('data.wallet_network', 'USDT_TRC20');

        $this->assertSame('USDT_TRC20', $user->fresh()->wallet_network);
    }

    public function test_unapproved_user_cannot_link_wallet(): void
    {
        $user = User::factory()->create(['approved_at' => null]);

        Sanctum::actingAs($user);

        $this->putJson('/api/wallet', [
            'wallet_network' => 'USDT_TRC20',
            'wallet_address' => 'TXk9ABCdEfGhIjKlMnOpQrStUvWxYz1234',
        ])->assertForbidden();
    }

    public function test_networks_endpoint_returns_options(): void
    {
        Sanctum::actingAs(User::factory()->create(['approved_at' => now()]));

        $this->getJson('/api/networks')
            ->assertOk()
            ->assertJsonStructure(['data' => [['value', 'label']]]);
    }
}
