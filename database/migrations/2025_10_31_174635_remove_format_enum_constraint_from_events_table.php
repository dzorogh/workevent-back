<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove CHECK constraint to allow any string value
        DB::statement("ALTER TABLE events DROP CONSTRAINT IF EXISTS events_format_check");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original CHECK constraint
        DB::statement("ALTER TABLE events ADD CONSTRAINT events_format_check CHECK (format = ANY (ARRAY['forum'::character varying, 'conference'::character varying, 'exhibition'::character varying, 'seminar'::character varying, 'webinar'::character varying, 'summit'::character varying]))");
    }
};
