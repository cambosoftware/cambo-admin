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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('log_name')->default('default')->index();
            $table->text('description');
            $table->nullableMorphs('subject'); // The model being logged
            $table->string('event')->nullable()->index(); // created, updated, deleted, etc.
            $table->nullableMorphs('causer'); // Usually the user
            $table->json('properties')->nullable(); // Old values, new values, etc.
            $table->string('batch_uuid')->nullable()->index(); // Group related logs
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
