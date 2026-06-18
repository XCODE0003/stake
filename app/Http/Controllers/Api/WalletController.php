<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class WalletController extends Controller
{
    /**
     * Link or update the payout wallet for the authenticated affiliate.
     */
    public function update(WalletUpdateRequest $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        $user->update($request->safe()->only(['wallet_network', 'wallet_address']));

        return new UserResource($user->fresh());
    }
}
