<?php

declare(strict_types=1);

namespace Tests\Feature\Mail;

use App\Models\Brand;
use App\Models\User;
use App\Notifications\VerifyEmailWithCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Markdown;
use Tests\TestCase;

class VerificationEmailBrandingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Render a notification's mail message to its final HTML, the way the mail
     * channel does, so assertions see exactly what lands in the inbox.
     */
    private function renderHtml(User $user): string
    {
        $mail = (new VerifyEmailWithCode)->toMail($user);

        return (string) app(Markdown::class)->render($mail->markdown, $mail->data());
    }

    public function test_email_body_is_branded_to_the_signup_site_not_stake(): void
    {
        // The global app name — what used to leak into every brand's email.
        config(['app.name' => 'Stake']);

        $brand = Brand::factory()->create([
            'name' => 'Zooma',
            'from_name' => 'Zooma Affiliate',
            'from_address' => 'no-reply@zoomaaffilates.com',
        ]);

        $user = User::factory()->unverified()->create(['brand_id' => $brand->id]);

        $html = $this->renderHtml($user);

        $this->assertStringContainsString('Zooma', $html);
        $this->assertStringNotContainsString('Stake', $html);
    }

    public function test_email_falls_back_to_the_app_name_without_a_brand(): void
    {
        config(['app.name' => 'Stake']);

        $user = User::factory()->unverified()->create(['brand_id' => null]);

        $html = $this->renderHtml($user);

        $this->assertStringContainsString('Stake', $html);
    }
}
