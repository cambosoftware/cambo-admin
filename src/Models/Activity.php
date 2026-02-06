<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Activity extends Model
{
    use HasUuids;

    protected $table = 'activity_logs';

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'event',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * The subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user that caused the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the old values from properties.
     */
    public function getOldAttribute(): array
    {
        return $this->properties['old'] ?? [];
    }

    /**
     * Get the new values from properties.
     */
    public function getNewAttribute(): array
    {
        return $this->properties['attributes'] ?? [];
    }

    /**
     * Get the changes (diff between old and new).
     */
    public function getChangesAttribute(): array
    {
        $old = $this->old;
        $new = $this->new;

        $changes = [];
        foreach ($new as $key => $value) {
            if (!array_key_exists($key, $old) || $old[$key] !== $value) {
                $changes[$key] = [
                    'old' => $old[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }

    /**
     * Scope by log name.
     */
    public function scopeInLog($query, ...$logNames)
    {
        return $query->whereIn('log_name', Arr::flatten($logNames));
    }

    /**
     * Scope by event type.
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope by subject.
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope by causer.
     */
    public function scopeCausedBy($query, Model $causer)
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    /**
     * Scope by batch.
     */
    public function scopeForBatch($query, string $batchUuid)
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    /**
     * Log an activity.
     */
    public static function log(string $description): ActivityBuilder
    {
        return new ActivityBuilder($description);
    }
}

/**
 * Activity builder for fluent API.
 */
class ActivityBuilder
{
    protected array $attributes = [];

    public function __construct(string $description)
    {
        $this->attributes['description'] = $description;
        $this->attributes['log_name'] = 'default';
    }

    public function inLog(string $logName): self
    {
        $this->attributes['log_name'] = $logName;
        return $this;
    }

    public function event(string $event): self
    {
        $this->attributes['event'] = $event;
        return $this;
    }

    public function on(Model $subject): self
    {
        $this->attributes['subject_type'] = $subject->getMorphClass();
        $this->attributes['subject_id'] = $subject->getKey();
        return $this;
    }

    public function by(?Model $causer): self
    {
        if ($causer) {
            $this->attributes['causer_type'] = $causer->getMorphClass();
            $this->attributes['causer_id'] = $causer->getKey();
        }
        return $this;
    }

    public function withProperties(array $properties): self
    {
        $this->attributes['properties'] = $properties;
        return $this;
    }

    public function batch(string $uuid): self
    {
        $this->attributes['batch_uuid'] = $uuid;
        return $this;
    }

    public function withRequest(): self
    {
        $this->attributes['ip_address'] = request()->ip();
        $this->attributes['user_agent'] = request()->userAgent();
        return $this;
    }

    public function save(): Activity
    {
        // Auto-set causer if not set and user is authenticated
        if (!isset($this->attributes['causer_type']) && auth()->check()) {
            $this->by(auth()->user());
        }

        return Activity::create($this->attributes);
    }
}
