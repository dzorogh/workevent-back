<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->string('title')->after('event_id');
            $table->boolean('is_active')->default(true)->after('conditions');
            $table->integer('sort_order')->default(0)->after('is_active');
            $table->renameColumn('conditions', 'description');
        });
    }

    public function down(): void
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn(['title', 'is_active', 'sort_order']);
            $table->renameColumn('description', 'conditions');
        });
    }
};
