<?php

declare(strict_types=1);

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
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('permalink')->unique();
            $table->boolean('published')->default(true);
            $table->string('title');
            $table->longText('text');
            $table->timestamps();

            $table->foreignUuid('user_id')
                ->nullable()
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        Schema::create('page_metadata', function (Blueprint $table) {
            $table->string('title');
            $table->string('description');
            $table->string('robots')->default('index,follow');
            $table->boolean('sitemap_include')->default(true);
            $table->decimal('sitemap_prio', 2, 1)->default(0.7);
            $table->string('sitemap_change_freq', 7)->default('monthly');

            $table->foreignUuid('page_id')
                ->primary()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
