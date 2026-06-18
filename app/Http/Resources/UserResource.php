<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'referral_code' => $this->referral_code,
            'is_admin' => $this->is_admin,
            'email_verified' => $this->hasVerifiedEmail(),
            'approved' => $this->isApproved(),
            'needs_approval' => $this->needsApproval(),
            'fixed_payment_amount' => $this->fixed_payment_amount,
            'fixed_payment_currency' => $this->fixed_payment_currency,
            'streams_count' => $this->streams_count,
            'referrals_count' => $this->referrals_count,
            'casino_profit' => $this->casino_profit,
            'your_profit' => $this->your_profit,
            'wallet_address' => $this->wallet_address,
            'wallet_network' => $this->wallet_network,
        ];
    }
}
