<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Widens tickets.status to every lifecycle state (adds reopened).
 *
 * Root cause: 2024_01_01_000004 hardcoded 10 statuses; the reopen flow
 * writes status=reopened, which fails on CHECK-enforcing databases. The
 * allowed list is derived from Ticket::TRANSITIONS so code and schema agree.
 *
 * Data-preserving: no rows modified, no columns dropped.
 */
return new class extends Migration
{
    public static function allowedStatuses(): array
    {
        return array_keys(\App\Models\Ticket::TRANSITIONS);
    }

    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            $enum = "'".implode("','", self::allowedStatuses())."'";
            DB::statement("ALTER TABLE tickets MODIFY status ENUM({$enum}) NOT NULL DEFAULT 'new'");

            return;
        }
        if ($driver === 'sqlite') {
            $this->rebuildSqliteTickets(self::allowedStatuses());

            return;
        }
        Schema::table('tickets', function ($table) {
            $table->string('status')->default('new')->change();
        });
    }

    public function down(): void
    {
        DB::table('tickets')->whereNotIn('status', [
            'new', 'open', 'assigned', 'in_progress', 'waiting_customer',
            'waiting_third_party', 'escalated', 'resolved', 'closed', 'cancelled',
        ])->update(['status' => 'open']);

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE tickets MODIFY status ENUM('new','open','assigned','in_progress','waiting_customer','waiting_third_party','escalated','resolved','closed','cancelled') NOT NULL DEFAULT 'new'");

            return;
        }
        if ($driver === 'sqlite') {
            $this->rebuildSqliteTickets([
                'new', 'open', 'assigned', 'in_progress', 'waiting_customer',
                'waiting_third_party', 'escalated', 'resolved', 'closed', 'cancelled',
            ]);

            return;
        }
        Schema::table('tickets', function ($table) {
            $table->string('status')->default('new')->change();
        });
    }

    /**
     * SQLite cannot ALTER a CHECK: rebuild tickets with identical DDL except
     * the widened status list. Indexes, triggers and data are preserved.
     */
    private function rebuildSqliteTickets(array $statuses): void
    {
        $ddl = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='tickets'")->sql;
        $list = "'".implode("','", $statuses)."'";
        $newDdl = preg_replace(
            '/"status" varchar check \("status" in \([^)]*\)\)/',
            '"status" varchar check ("status" in ('.$list.'))',
            $ddl,
            1
        );
        abort_if($newDdl === null || $newDdl === $ddl, 500, 'tickets.status CHECK pattern not found; refusing to rebuild.');

        $indexes = DB::select("SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='tickets' AND sql IS NOT NULL");
        $triggers = DB::select("SELECT sql FROM sqlite_master WHERE type='trigger' AND tbl_name='tickets'");

        Schema::disableForeignKeyConstraints();
        try {
            DB::statement(str_replace('CREATE TABLE "tickets"', 'CREATE TABLE "tickets_new"', $newDdl));
            DB::statement('INSERT INTO "tickets_new" SELECT * FROM "tickets"');
            DB::statement('DROP TABLE "tickets"');
            DB::statement('ALTER TABLE "tickets_new" RENAME TO "tickets"');
            foreach ($indexes as $index) {
                DB::statement($index->sql);
            }
            foreach ($triggers as $trigger) {
                DB::statement($trigger->sql);
            }
            DB::statement("UPDATE sqlite_sequence SET seq = (SELECT COALESCE(MAX(id), 0) FROM tickets) WHERE name='tickets'");
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
};
