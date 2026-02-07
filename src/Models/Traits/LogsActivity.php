<?php

namespace CamboSoftware\CamboAdmin\Models\Traits;

use CamboSoftware\CamboAdmin\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            if ($model->shouldLogActivity()) {
                $model->logActivity('created');
            }
        });

        static::updated(function ($model) {
            if ($model->shouldLogActivity() && $model->wasChanged()) {
                $model->logActivity('updated');
            }
        });

        static::deleted(function ($model) {
            if ($model->shouldLogActivity()) {
                $model->logActivity('deleted');
            }
        });
    }

    /**
     * Get all activities for this model.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Check if activity should be logged.
     */
    protected function shouldLogActivity(): bool
    {
        return !$this->activityLogDisabled ?? true;
    }

    /**
     * Temporarily disable activity logging.
     */
    public function disableActivityLog(): self
    {
        $this->activityLogDisabled = true;
        return $this;
    }

    /**
     * Enable activity logging.
     */
    public function enableActivityLog(): self
    {
        $this->activityLogDisabled = false;
        return $this;
    }

    /**
     * Log an activity for this model.
     */
    public function logActivity(string $event, ?string $description = null): Activity
    {
        $description = $description ?? $this->getDefaultDescription($event);
        $properties = $this->getActivityProperties($event);

        return Activity::log($description)
            ->inLog($this->getLogName())
            ->event($event)
            ->on($this)
            ->by(auth()->user())
            ->withProperties($properties)
            ->withRequest()
            ->save();
    }

    /**
     * Get the log name for this model.
     */
    protected function getLogName(): string
    {
        return property_exists($this, 'logName')
            ? $this->logName
            : Str::snake(class_basename($this));
    }

    /**
     * Get the default description for an event.
     */
    protected function getDefaultDescription(string $event): string
    {
        $modelName = $this->getActivitySubjectDescription();

        return match ($event) {
            'created' => "{$modelName} was created",
            'updated' => "{$modelName} was updated",
            'deleted' => "{$modelName} was deleted",
            default => "{$modelName} - {$event}",
        };
    }

    /**
     * Get a description of the subject for activity log.
     */
    protected function getActivitySubjectDescription(): string
    {
        if (method_exists($this, 'getActivityDescription')) {
            return $this->getActivityDescription();
        }

        $name = $this->name ?? $this->title ?? $this->email ?? $this->getKey();

        return class_basename($this) . ' "' . $name . '"';
    }

    /**
     * Get the properties to log for an event.
     */
    protected function getActivityProperties(string $event): array
    {
        $properties = [];

        if ($event === 'updated') {
            $properties['old'] = $this->getOldValuesToLog();
            $properties['attributes'] = $this->getNewValuesToLog();
        } elseif ($event === 'created') {
            $properties['attributes'] = $this->getNewValuesToLog();
        } elseif ($event === 'deleted') {
            $properties['old'] = $this->getDeletedValuesToLog();
        }

        return $properties;
    }

    /**
     * Get the old values to log.
     */
    protected function getOldValuesToLog(): array
    {
        $dirty = array_keys($this->getDirty());
        $loggableAttributes = $this->getLoggableAttributes();

        if ($loggableAttributes !== ['*']) {
            $dirty = array_intersect($dirty, $loggableAttributes);
        }

        $dirty = array_diff($dirty, $this->getExcludedAttributes());

        return collect($this->getOriginal())
            ->only($dirty)
            ->toArray();
    }

    /**
     * Get the new values to log.
     */
    protected function getNewValuesToLog(): array
    {
        $dirty = $this->getDirty();
        $loggableAttributes = $this->getLoggableAttributes();

        if ($loggableAttributes !== ['*']) {
            $dirty = array_intersect_key($dirty, array_flip($loggableAttributes));
        }

        return collect($dirty)
            ->except($this->getExcludedAttributes())
            ->toArray();
    }

    /**
     * Get the deleted values to log.
     */
    protected function getDeletedValuesToLog(): array
    {
        $loggableAttributes = $this->getLoggableAttributes();
        $attributes = $this->getAttributes();

        if ($loggableAttributes !== ['*']) {
            $attributes = array_intersect_key($attributes, array_flip($loggableAttributes));
        }

        return collect($attributes)
            ->except($this->getExcludedAttributes())
            ->toArray();
    }

    /**
     * Get the attributes that should be logged.
     */
    protected function getLoggableAttributes(): array
    {
        return property_exists($this, 'loggableAttributes')
            ? $this->loggableAttributes
            : ['*'];
    }

    /**
     * Get the attributes that should not be logged.
     */
    protected function getExcludedAttributes(): array
    {
        $defaults = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

        $custom = property_exists($this, 'excludedFromLog')
            ? $this->excludedFromLog
            : [];

        return array_merge($defaults, $custom);
    }
}
