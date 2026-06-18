<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Filament admin / manager access.
            $table->boolean('is_admin')->default(false)->after('password');

            // Manager approval of the account.
            $table->timestamp('approved_at')->nullable()->after('is_admin');

            // Manager-controlled partner figures.
            $table->decimal('fixed_payment_amount', 12, 2)->default(0)->after('approved_at');
            $table->string('fixed_payment_currency', 8)->default('USDT')->after('fixed_payment_amount');
            $table->unsignedInteger('streams_count')->default(0)->after('fixed_payment_currency');
            $table->unsignedInteger('referrals_count')->default(0)->after('streams_count');

            // Payout wallet linked by the user.
            $table->string('wallet_address')->nullable()->after('referrals_count');
            $table->string('wallet_network', 32)->nullable()->after('wallet_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin',
                'approved_at',
                'fixed_payment_amount',
                'fixed_payment_currency',
                'streams_count',
                'referrals_count',
                'wallet_address',
                'wallet_network',
            ]);
        });
    }
};
