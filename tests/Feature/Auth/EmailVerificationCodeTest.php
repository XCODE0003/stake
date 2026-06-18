<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Support\EmailVerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_email_with_correct_code(): void
    {
        $user = User::factory()->unverified()->create();
        $code = app(EmailVerificationCode::class)->generate($user);

        $this->actingAs($user)
            ->post('/email/verify-code', ['code' => $code])
            ->assertRedirect('/dashboard');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_cannot_verify_email_with_wrong_code(): void
    {
        $user = User::factory()->unverified()->create();
        app(EmailVerificationCode::class)->generate($user);

        $this->actingAs($user)
            ->post('/email/verify-code', ['code' => '000000'])
            ->assertSessionHasErrors('code');

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_code_is_single_use(): void
    {
        $user = User::factory()->unverified()->create();
        $codes = app(EmailVerificationCode::class);
        $code = $codes->generate($user);

        $this->assertTrue($codes->verify($user, $code));
        $this->assertFalse($codes->verify($user, $code));
    }
}
