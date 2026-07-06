<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the brands table: one row per public site we run. Each brand
     * carries the domain we match incoming signups by (from the referrer) and
     * its own SMTP credentials + sender identity, all editable in the admin.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Hostname we match the request referrer against, e.g. "vodka.com".
            $table->string('domain')->nullable();
            // Outbound sender identity.
            $table->string('from_name')->nullable();
            $table->string('from_address')->nullable();
            // SMTP credentials for this brand's mailbox.
            $table->string('mail_host')->nullable();
            $table->unsignedInteger('mail_port')->nullable()->default(587);
            $table->string('mail_username')->nullable();
            $table->text('mail_password')->nullable();
            // null = plain, "ssl" = implicit TLS (465), "tls" = STARTTLS (587).
            $table->string('mail_encryption')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
