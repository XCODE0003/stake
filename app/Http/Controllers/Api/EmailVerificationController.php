<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Support\EmailVerificationCode;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    /**
     * Confirm the email address with the one-time code.
     */
    public function verify(Request $request, EmailVerificationCode $codes): UserResource
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:'.EmailVerificationCode::LENGTH],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            if (! $codes->verify($user, $request->string('code')->toString())) {
                throw ValidationException::withMessages([
                    'code' => 'Invalid or expired code. Request a new one and try again.',
                ]);
            }

            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return new UserResource($user->fresh());
    }

    /**
     * Send a fresh verification code.
     */
    public function resend(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return response()->json(['message' => 'A new code has been sent to your email.']);
    }
}
