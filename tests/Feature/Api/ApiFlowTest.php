<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\User;
use App\Notifications\VerifyEmailWithCode;
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

    public function test_register_attributes_the_signup_to_the_referrer_brand(): void
    {
        $brand = Brand::factory()->create([
            'name' => 'Vodka',
            'domain' => 'vodka-partners.com',
        ]);

        $this->postJson('/api/register', [
            'name' => 'streamer',
            'email' => 'streamer@example.com',
            'password' => 'password',
            'terms' => true,
        ], ['Referer' => 'https://vodka-partners.com/signup'])
            ->assertCreated()
            ->assertJsonPath('data.brand', 'Vodka');

        $this->assertDatabaseHas('users', [
            'email' => 'streamer@example.com',
            'brand_id' => $brand->id,
        ]);
    }

    public function test_register_without_a_matching_referrer_has_no_brand(): void
    {
        Brand::factory()->create(['domain' => 'vodka-partners.com']);

        $this->postJson('/api/register', [
            'name' => 'streamer',
            'email' => 'streamer@example.com',
            'password' => 'password',
            'terms' => true,
        ], ['Referer' => 'https://unknown-site.com/signup'])
            ->assertCreated()
            ->assertJsonPath('data.brand', null);

        $this->assertDatabaseHas('users', [
            'email' => 'streamer@example.com',
            'brand_id' => null,
        ]);
    }

    public function test_register_ignores_inactive_brands(): void
    {
        Brand::factory()->create([
            'domain' => 'vodka-partners.com',
            'is_active' => false,
        ]);

        $this->postJson('/api/register', [
            'name' => 'streamer',
            'email' => 'streamer@example.com',
            'password' => 'password',
            'terms' => true,
        ], ['Referer' => 'https://vodka-partners.com/signup'])
            ->assertCreated()
            ->assertJsonPath('data.brand', null);
    }

    public function test_verification_email_is_sent_via_the_brand_smtp(): void
    {
        $brand = Brand::factory()->withSmtp()->create([
            'name' => 'Vodka',
            'from_name' => 'Vodka',
            'from_address' => 'no-reply@vodka.example',
            'mail_host' => 'smtp.vodka.example',
        ]);

        $user = User::factory()->unverified()->create(['brand_id' => $brand->id]);

        $mail = (new VerifyEmailWithCode)->toMail($user);

        $this->assertSame('Vodka — verification code', $mail->subject);
        $this->assertSame(['no-reply@vodka.example', 'Vodka'], $mail->from);
        $this->assertSame('brand_'.$brand->id, $mail->mailer);
        $this->assertSame('smtp.vodka.example', config('mail.mailers.brand_'.$brand->id.'.host'));
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
