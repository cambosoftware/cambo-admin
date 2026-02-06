<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MediaFolder extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'slug',
        'color',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($folder) {
            if (empty($folder->slug)) {
                $folder->slug = Str::slug($folder->name);
            }
        });
    }

    /**
     * The user that owns the folder.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The parent folder.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Child folders.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Files in this folder.
     */
    public function files(): HasMany
    {
        return $this->hasMany(MediaFile::class, 'folder_id');
    }

    /**
     * Get the full path of the folder.
     */
    public function getPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode('/', $path);
    }

    /**
     * Get breadcrumb for the folder.
     */
    public function getBreadcrumb(): array
    {
        $breadcrumb = [['id' => $this->id, 'name' => $this->name]];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($breadcrumb, ['id' => $parent->id, 'name' => $parent->name]);
            $parent = $parent->parent;
        }

        return $breadcrumb;
    }

    /**
     * Get all descendants (nested children).
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Count files recursively.
     */
    public function getFilesCountAttribute(): int
    {
        $count = $this->files()->count();

        foreach ($this->children as $child) {
            $count += $child->files_count;
        }

        return $count;
    }
}
