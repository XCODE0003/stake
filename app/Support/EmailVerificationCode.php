<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Generates and verifies the short numeric code used to confirm a user's
 * email address. Codes are stored hashed in the cache with a short TTL so
 * they expire on their own and never live in the database.
 */
class EmailVerificationCode
{
    /**
     * Number of digits in a generated code.
     */
    public const int LENGTH = 6;

    /**
     * How long a code stays valid, in minutes.
     */
    public const int TTL_MINUTES = 15;

    /**
     * Create a fresh code for the user and return the plaintext value.
     */
    public function generate(User $user): string
    {
        $code = (string) random_int(100000, 999999);

        Cache::put($this->cacheKey($user), Hash::make($code), now()->addMinutes(self::TTL_MINUTES));

        return $code;
    }

    /**
     * Verify a submitted code. A correct code is consumed (single use).
     */
    public function verify(User $user, string $code): bool
    {
        $hashed = Cache::get($this->cacheKey($user));

        if (! is_string($hashed) || ! Hash::check($code, $hashed)) {
            return false;
        }

        Cache::forget($this->cacheKey($user));

        return true;
    }

    private function cacheKey(User $user): string
    {
        return "email-verification-code:{$user->getKey()}";
    }
}
