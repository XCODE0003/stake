<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Record the raw referrer domain a signup came from. Unlike brand_id this
     * survives when the domain matches no configured site, so managers can see
     * where an unattributed affiliate actually registered.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('signup_domain')->nullable()->after('brand_id');
            $table->index('signup_domain');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['signup_domain']);
            $table->dropColumn('signup_domain');
        });
    }
};
