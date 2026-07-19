<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApproveActionTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'approved_at' => now()]);
    }

    public function test_approve_action_sets_approved_at(): void
    {
        $pending = User::factory()->create([
            'email_verified_at' => now(),
            'approved_at' => null,
        ]);

        $this->actingAs($this->admin());

        Livewire::test(ListUsers::class)
            ->callTableAction('approve', $pending);

        $this->assertNotNull($pending->refresh()->approved_at);
    }

    public function test_revoke_action_clears_approved_at(): void
    {
        $approved = User::factory()->create([
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $this->actingAs($this->admin());

        Livewire::test(ListUsers::class)
            ->callTableAction('revoke', $approved);

        $this->assertNull($approved->refresh()->approved_at);
    }
}
