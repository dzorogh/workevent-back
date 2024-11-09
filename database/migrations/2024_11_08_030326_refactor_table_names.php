<?php

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
        Schema::rename('event_series', 'series');
        Schema::rename('event_tags', 'tags');
        Schema::rename('event_tariffs', 'tariffs');

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('main_industry_id', 'industry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('industry_id', 'main_industry_id');
        });

        Schema::rename('series', 'event_series');
        Schema::rename('tags', 'event_tags');
        Schema::rename('tariffs', 'event_tariffs');
    }
};
