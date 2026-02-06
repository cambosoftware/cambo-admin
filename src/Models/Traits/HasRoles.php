<?php

namespace CamboSoftware\CamboAdmin\Models\Traits;

use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * Boot the trait.
     */
    public static function bootHasRoles(): void
    {
        static::created(function ($user) {
            // Assign default role on user creation
            if ($defaultRole = Role::getDefault()) {
                $user->roles()->attach($defaultRole);
            }
        });
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    /**
     * Assign roles to the user.
     */
    public function assignRole(string|array|Role $roles): self
    {
        $roles = is_array($roles) ? $roles : [$roles];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $role = Role::where('slug', $role)->first();
            }

            if ($role && !$this->hasRole($role->slug)) {
                $this->roles()->attach($role);
            }
        }

        return $this;
    }

    /**
     * Remove roles from the user.
     */
    public function removeRole(string|array|Role $roles): self
    {
        $roles = is_array($roles) ? $roles : [$roles];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $role = Role::where('slug', $role)->first();
            }

            if ($role) {
                $this->roles()->detach($role);
            }
        }

        return $this;
    }

    /**
     * Sync roles for the user.
     */
    public function syncRoles(array $roles): self
    {
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('slug', $role)->first()?->id;
            }
            return $role instanceof Role ? $role->id : $role;
        })->filter()->toArray();

        $this->roles()->sync($roleIds);

        return $this;
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('slug', $role)->exists();
        }

        return $this->roles()->where('roles.id', $role->id)->exists();
    }

    /**
     * Check if the user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if the user has all of the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->count() === count($roles);
    }

    /**
     * Get all permissions for the user through their roles.
     */
    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(fn ($role) => $role->permissions)
            ->unique('id');
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->getAllPermissions()->contains('slug', $permission);
    }

    /**
     * Check if the user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->getAllPermissions()->whereIn('slug', $permissions)->isNotEmpty();
    }

    /**
     * Check if the user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        $userPermissions = $this->getAllPermissions()->pluck('slug');
        return collect($permissions)->every(fn ($p) => $userPermissions->contains($p));
    }

    /**
     * Check if the user can perform an action (alias for hasPermission).
     */
    public function can($ability, $arguments = []): bool
    {
        // If it's a permission slug (contains dot or dash), check permission
        if (is_string($ability) && (str_contains($ability, '.') || str_contains($ability, '-'))) {
            return $this->hasPermission($ability);
        }

        // Otherwise, use Laravel's default authorization
        return parent::can($ability, $arguments);
    }
}
