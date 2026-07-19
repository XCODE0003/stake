<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use App\Support\Brands;
use App\Support\EmailVerificationCode;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sends the user a one-time numeric code to confirm their email address,
 * replacing Fortify's default link-based verification.
 */
class VerifyEmailWithCode extends Notification
{
    use Queueable;

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        /** @var User $notifiable */
        $code = app(EmailVerificationCode::class)->generate($notifiable);

        $message = new MailMessage;

        // Send from the brand/site the affiliate registered on, using that
        // brand's own SMTP mailbox. Falls back to the app's default mail config
        // when the signup was not attributed to any brand.
        $brand = $notifiable->brand;
        $name = $brand?->name ?: (string) config('app.name');

        if ($brand !== null) {
            if ($brand->hasSmtp()) {
                $message->mailer(app(Brands::class)->configureMailer($brand));
            }
            $message->from($brand->fromAddress(), $brand->fromName());
        }

        // A dedicated Blade view so the whole email — header, footer, title and
        // salutation, not just the body — is branded to $name rather than the
        // global app name (which is "Stake").
        return $message
            ->subject($name.' — verification code')
            ->markdown('mail.verification-code', [
                'brandName' => $name,
                'code' => $code,
                'ttl' => EmailVerificationCode::TTL_MINUTES,
            ]);
    }
}
