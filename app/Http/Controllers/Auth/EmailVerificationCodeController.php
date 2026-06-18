<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\EmailVerificationCode;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerificationCodeController extends Controller
{
    /**
     * Verify the code the user received by email.
     */
    public function store(Request $request, EmailVerificationCode $codes): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:'.EmailVerificationCode::LENGTH],
        ]);

        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(config('fortify.home'));
        }

        if (! $codes->verify($user, $request->string('code')->toString())) {
            throw ValidationException::withMessages([
                'code' => 'Неверный или просроченный код. Запросите новый и попробуйте снова.',
            ]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->intended(config('fortify.home'));
    }

    /**
     * Send a fresh verification code.
     */
    public function resend(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(config('fortify.home'));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-code-sent');
    }
}
