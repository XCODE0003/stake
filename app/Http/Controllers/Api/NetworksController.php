<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\PartnerOptions;
use Illuminate\Http\JsonResponse;

class NetworksController extends Controller
{
    /**
     * Payout network options for the wallet form.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(['data' => PartnerOptions::networkOptions()]);
    }
}
