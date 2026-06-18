<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\WalletUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class WalletController extends Controller
{
    /**
     * Link or update the payout wallet for the authenticated partner.
     */
    public function update(WalletUpdateRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update($request->safe()->only(['wallet_network', 'wallet_address']));

        return back()->with('status', 'wallet-updated');
    }
}
