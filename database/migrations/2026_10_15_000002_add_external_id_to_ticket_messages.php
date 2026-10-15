<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            // Provider message-id for idempotent inbound-email ingestion.
            // Null for UI-created messages; unique when present.
            $table->string('external_id', 191)->nullable()->unique()->after('is_internal_note');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};
