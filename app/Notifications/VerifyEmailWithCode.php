<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
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

        return (new MailMessage)
            ->subject('Stake — verification code')
            ->greeting('Confirm your email')
            ->line('Your Stake verification code is:')
            ->line('**'.$code.'**')
            ->line('The code is valid for '.EmailVerificationCode::TTL_MINUTES.' minutes.')
            ->line('If you did not register, you can safely ignore this email.');
    }
}
