<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\VerifyEmailWithCode;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $referral_code
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property bool $is_admin
 * @property Carbon|null $approved_at
 * @property string $fixed_payment_amount
 * @property string $fixed_payment_currency
 * @property int $streams_count
 * @property int $referrals_count
 * @property string $casino_profit
 * @property string $your_profit
 * @property string|null $wallet_address
 * @property string|null $wallet_network
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'email',
    'referral_code',
    'password',
    'is_admin',
    'approved_at',
    'fixed_payment_amount',
    'fixed_payment_currency',
    'streams_count',
    'referrals_count',
    'casino_profit',
    'your_profit',
    'wallet_address',
    'wallet_network',
])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'approved_at' => 'datetime',
            'fixed_payment_amount' => 'decimal:2',
            'streams_count' => 'integer',
            'referrals_count' => 'integer',
            'casino_profit' => 'decimal:2',
            'your_profit' => 'decimal:2',
            /* @chisel-2fa */
            'two_factor_confirmed_at' => 'datetime',
            /* @end-chisel-2fa */
        ];
    }

    /**
     * Whether a manager has approved this account.
     */
    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    /**
     * Whether the account is verified but still waiting for manager approval.
     */
    public function needsApproval(): bool
    {
        return $this->hasVerifiedEmail() && ! $this->isApproved();
    }

    /**
     * Send the email verification notification using a one-time code.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailWithCode);
    }

    /**
     * Support tickets opened by this user.
     *
     * @return HasMany<Ticket, $this>
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Only admins/managers may access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }
}
