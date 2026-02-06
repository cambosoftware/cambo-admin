<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFile extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'folder_id',
        'name',
        'original_name',
        'disk',
        'path',
        'mime_type',
        'type',
        'extension',
        'size',
        'metadata',
        'alt_text',
        'description',
        'is_public',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'size' => 'integer',
    ];

    protected $appends = ['url', 'thumbnail_url', 'human_size'];

    /**
     * The user that uploaded the file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The folder containing the file.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    /**
     * Get the full URL of the file.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Get the thumbnail URL (for images).
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->type !== 'image') {
            return null;
        }

        // Could implement thumbnail generation here
        return $this->url;
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'Ko', 'Mo', 'Go', 'To'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Determine file type from mime type.
     */
    public static function determineType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        if (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
        ])) {
            return 'document';
        }
        if (in_array($mimeType, [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            'application/x-tar',
            'application/gzip',
        ])) {
            return 'archive';
        }

        return 'other';
    }

    /**
     * Create a media file from an uploaded file.
     */
    public static function fromUpload(
        \Illuminate\Http\UploadedFile $file,
        ?int $userId = null,
        ?int $folderId = null,
        string $disk = 'public'
    ): self {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $type = self::determineType($mimeType);

        // Generate unique filename
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $uniqueName = Str::slug($name) . '-' . Str::random(8) . '.' . $extension;

        // Store file
        $path = $file->storeAs(
            'media/' . date('Y/m'),
            $uniqueName,
            $disk
        );

        // Get image dimensions if applicable
        $metadata = [];
        if ($type === 'image') {
            $dimensions = getimagesize($file->getRealPath());
            if ($dimensions) {
                $metadata['width'] = $dimensions[0];
                $metadata['height'] = $dimensions[1];
            }
        }

        return self::create([
            'user_id' => $userId,
            'folder_id' => $folderId,
            'name' => $name,
            'original_name' => $originalName,
            'disk' => $disk,
            'path' => $path,
            'mime_type' => $mimeType,
            'type' => $type,
            'extension' => $extension,
            'size' => $size,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Delete the file from storage.
     */
    public function deleteFromStorage(): bool
    {
        return Storage::disk($this->disk)->delete($this->path);
    }

    /**
     * Move file to another folder.
     */
    public function moveToFolder(?int $folderId): self
    {
        $this->update(['folder_id' => $folderId]);
        return $this;
    }

    /**
     * Scope for filtering by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by folder.
     */
    public function scopeInFolder($query, ?int $folderId)
    {
        return $query->where('folder_id', $folderId);
    }

    /**
     * Scope for search.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('original_name', 'like', "%{$search}%");
        });
    }
}
