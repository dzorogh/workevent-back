<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->morphs('metadatable');

            // Basic SEO
            $table->string('h1')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('robots')->nullable();

            // Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_type')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_url')->nullable();
            $table->string('og_site_name')->nullable();
            $table->string('og_locale')->nullable();

            // Twitter
            $table->string('tw_card')->nullable();
            $table->string('tw_title')->nullable();
            $table->text('tw_description')->nullable();
            $table->string('tw_image')->nullable();
            $table->string('tw_site')->nullable();
            $table->string('tw_creator')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
