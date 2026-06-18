<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('casino_profit', 14, 2)->default(0)->after('referrals_count');
            $table->decimal('your_profit', 14, 2)->default(0)->after('casino_profit');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['casino_profit', 'your_profit']);
        });
    }
};
