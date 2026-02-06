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
        // Drop existing notifications table if exists (Laravel's default)
        Schema::dropIfExists('notifications');

        // Create enhanced notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // Type de notification (info, success, warning, danger)
            $table->string('icon')->nullable(); // Icône Heroicon
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('action_url')->nullable(); // URL d'action
            $table->string('action_text')->nullable(); // Texte du bouton d'action
            $table->json('data')->nullable(); // Données additionnelles
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index('created_at');
        });

        // Notification preferences
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('channel'); // email, database, push
            $table->string('type'); // Type de notification
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'channel', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
};
