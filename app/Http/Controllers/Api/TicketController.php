<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends Controller
{
    /**
     * List the authenticated user's support tickets.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        return TicketResource::collection($user->tickets()->latest()->get());
    }

    /**
     * Open a new support ticket.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $ticket = $user->tickets()->create([
            'message' => $data['message'],
            'status' => Ticket::STATUS_OPEN,
        ]);

        return (new TicketResource($ticket))->response()->setStatusCode(201);
    }
}
