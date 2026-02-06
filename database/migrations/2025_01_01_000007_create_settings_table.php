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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // Category/group
            $table->string('key')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('type')->default('text'); // text, textarea, number, boolean, select, multiselect, json, color, file
            $table->text('value')->nullable();
            $table->text('default_value')->nullable();
            $table->json('options')->nullable(); // For select/multiselect
            $table->json('validation')->nullable(); // Validation rules
            $table->boolean('is_public')->default(false); // Accessible without auth
            $table->boolean('is_encrypted')->default(false); // Store encrypted
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['group', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
