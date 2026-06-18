<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique(User::class, 'name')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', Password::default()],
            'referral_code' => ['nullable', 'string', 'max:64'],
            'terms' => ['accepted'],
        ], [
            'name.unique' => 'This username is already taken.',
            'name.min' => 'The username must be at least 3 characters.',
            'terms.accepted' => 'You must confirm that you are 18 or older and agree to the Terms and Conditions.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'referral_code' => $input['referral_code'] ?? null,
        ]);
    }
}
