<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Manager / admin — can log in to /admin and approve accounts.
        User::updateOrCreate(
            ['email' => 'admin@stake.test'],
            [
                'name' => 'Менеджер Stake',
                'password' => 'password',
                'is_admin' => true,
                'email_verified_at' => now(),
                'approved_at' => now(),
            ],
        );

        // Already-approved partner with sample figures and a linked wallet.
        User::updateOrCreate(
            ['email' => 'partner@stake.test'],
            [
                'name' => 'Активный партнёр',
                'password' => 'password',
                'email_verified_at' => now(),
                'approved_at' => now(),
                'fixed_payment_amount' => 1500,
                'fixed_payment_currency' => 'USDT',
                'streams_count' => 12,
                'referrals_count' => 5,
                'wallet_network' => 'USDT_TRC20',
                'wallet_address' => 'TXk9ABCdEfGhIjKlMnOpQrStUvWxYz1234',
            ],
        );

        // Verified partner still waiting for manager approval.
        User::updateOrCreate(
            ['email' => 'pending@stake.test'],
            [
                'name' => 'Партнёр на модерации',
                'password' => 'password',
                'email_verified_at' => now(),
                'approved_at' => null,
            ],
        );
    }
}
