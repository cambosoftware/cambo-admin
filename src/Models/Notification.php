<?php

namespace CamboSoftware\CamboAdmin\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'title',
        'body',
        'action_url',
        'action_text',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * The user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the notification has been read.
     */
    public function isRead(): bool
    {
        return ! is_null($this->read_at);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): self
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }

        return $this;
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): self
    {
        $this->update(['read_at' => null]);

        return $this;
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Create a notification for a user.
     */
    public static function notify(User $user, array $data): self
    {
        return static::create([
            'user_id' => $user->id,
            'type' => $data['type'] ?? 'info',
            'icon' => $data['icon'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'action_text' => $data['action_text'] ?? null,
            'data' => $data['data'] ?? null,
        ]);
    }

    /**
     * Notify multiple users.
     */
    public static function notifyMany($users, array $data): void
    {
        foreach ($users as $user) {
            static::notify($user, $data);
        }
    }
}
