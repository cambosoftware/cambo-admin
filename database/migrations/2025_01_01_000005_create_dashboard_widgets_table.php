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
        // Available widgets definitions
        Schema::create('widget_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('component'); // Vue component name
            $table->string('icon')->nullable();
            $table->json('default_config')->nullable();
            $table->json('config_schema')->nullable(); // JSON Schema for config
            $table->integer('min_width')->default(1);
            $table->integer('min_height')->default(1);
            $table->integer('default_width')->default(1);
            $table->integer('default_height')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // User dashboard layouts
        Schema::create('dashboard_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('default');
            $table->boolean('is_default')->default(false);
            $table->integer('columns')->default(12);
            $table->timestamps();

            $table->unique(['user_id', 'name']);
        });

        // User widgets instances
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_id')->constrained('dashboard_layouts')->cascadeOnDelete();
            $table->foreignId('widget_type_id')->constrained('widget_types')->cascadeOnDelete();
            $table->integer('x')->default(0); // Grid position X
            $table->integer('y')->default(0); // Grid position Y
            $table->integer('width')->default(1);
            $table->integer('height')->default(1);
            $table->json('config')->nullable(); // Widget-specific config
            $table->timestamps();

            $table->index(['layout_id', 'y', 'x']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('dashboard_layouts');
        Schema::dropIfExists('widget_types');
    }
};
