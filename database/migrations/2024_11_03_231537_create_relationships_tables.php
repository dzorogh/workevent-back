<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->enum('participation_type', [
                'organizer',
                'sponsor',
                'participant',
                'partner'
            ]);
            $table->index(['company_id', 'event_id']);
            $table->timestamps();
        });

        Schema::create('speaker_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('speaker_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->text('description')->nullable();
            $table->index(['speaker_id', 'event_id']);
            $table->timestamps();
        });

        Schema::create('event_tag', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained('event_tags');
            $table->foreignId('event_id')->constrained();
            $table->primary(['tag_id', 'event_id']);
            $table->timestamps();
        });

        Schema::create('event_tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->text('conditions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_event');
        Schema::dropIfExists('speaker_topics');
        Schema::dropIfExists('speaker_events');
        Schema::dropIfExists('event_tag');
        Schema::dropIfExists('event_tariffs');
    }
};
