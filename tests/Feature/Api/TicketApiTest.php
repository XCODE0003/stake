<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_open_a_ticket(): void
    {
        $user = User::factory()->create(['approved_at' => now()]);
        Sanctum::actingAs($user);

        $this->postJson('/api/tickets', ['message' => 'I need help with my payout.'])
            ->assertCreated()
            ->assertJsonPath('data.status', 'open');

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'status' => 'open',
        ]);
    }

    public function test_ticket_message_is_validated(): void
    {
        Sanctum::actingAs(User::factory()->create(['approved_at' => now()]));

        $this->postJson('/api/tickets', ['message' => 'hi'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }

    public function test_user_only_sees_own_tickets(): void
    {
        $user = User::factory()->create(['approved_at' => now()]);
        $other = User::factory()->create(['approved_at' => now()]);

        Ticket::create(['user_id' => $user->id, 'message' => 'mine please help']);
        Ticket::create(['user_id' => $other->id, 'message' => 'theirs please help']);

        Sanctum::actingAs($user);

        $this->getJson('/api/tickets')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.message', 'mine please help');
    }

    public function test_adding_a_reply_marks_ticket_answered(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::create(['user_id' => $user->id, 'message' => 'please help me']);

        $ticket->update(['reply' => 'Sure, here is the answer.']);

        $ticket->refresh();
        $this->assertSame('answered', $ticket->status);
        $this->assertNotNull($ticket->replied_at);
    }
}
