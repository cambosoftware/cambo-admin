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
        // Media folders table (must be created first)
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('media_folders')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('color')->nullable();
            $table->timestamps();

            $table->unique(['parent_id', 'slug']);
            $table->index('user_id');
        });

        // Media files table
        Schema::create('media_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->nullOnDelete();
            $table->string('name');
            $table->string('original_name');
            $table->string('disk')->default('public'); // local, s3, etc.
            $table->string('path');
            $table->string('mime_type');
            $table->string('type'); // image, video, document, audio, archive, other
            $table->string('extension');
            $table->unsignedBigInteger('size'); // bytes
            $table->json('metadata')->nullable(); // width, height, duration, etc.
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'type']);
            $table->index(['folder_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('media_folders');
    }
};
