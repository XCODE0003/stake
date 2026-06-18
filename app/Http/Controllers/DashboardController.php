<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PartnerOptions;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the partner dashboard, or a "waiting for approval" screen while a
     * manager has not yet confirmed the account.
     */
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->needsApproval()) {
            return Inertia::render('PendingApproval', [
                'email' => $user->email,
            ]);
        }

        return Inertia::render('Dashboard', [
            'stats' => [
                'fixedPaymentAmount' => $user->fixed_payment_amount,
                'fixedPaymentCurrency' => $user->fixed_payment_currency,
                'streamsCount' => $user->streams_count,
                'referralsCount' => $user->referrals_count,
            ],
            'wallet' => [
                'address' => $user->wallet_address,
                'network' => $user->wallet_network,
            ],
            'networkOptions' => PartnerOptions::networkOptions(),
        ]);
    }
}
