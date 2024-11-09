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
        Schema::rename('speaker_events', 'event_speaker');
        Schema::rename('speaker_topics', 'topics');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('series', 'event_series');
        Schema::rename('tags', 'event_tags');
        Schema::rename('tariffs', 'event_tariffs');
        Schema::rename('event_speaker', 'speaker_event');
        Schema::rename('topics', 'speaker_topics');
    }
};
