<?php

namespace App\Http\Controllers;

use CamboSoftware\CamboAdmin\Models\Permission;
use CamboSoftware\CamboAdmin\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->when($request->search, fn ($query, $search) =>
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return Inertia::render('Roles/Create', [
            'permissions' => Permission::getGrouped(),
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if (! empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect('/admin/roles')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users' => fn ($q) => $q->limit(10)]);
        $role->loadCount('users');

        return Inertia::render('Roles/Show', [
            'role' => $role,
            'permissionsGrouped' => Permission::getGrouped(),
        ]);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');

        return Inertia::render('Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'is_default' => $role->is_default,
                'permissions' => $role->permissions->pluck('slug')->toArray(),
            ],
            'permissions' => Permission::getGrouped(),
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('roles', 'slug')->ignore($role->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,slug'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect('/admin/roles')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of super-admin role
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'Le rôle Super Administrateur ne peut pas être supprimé.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Ce rôle est assigné à des utilisateurs. Veuillez d\'abord réassigner ces utilisateurs.');
        }

        $role->delete();

        return redirect('/admin/roles')
            ->with('success', 'Rôle supprimé avec succès.');
    }

    /**
     * Set a role as the default role.
     */
    public function setDefault(Role $role)
    {
        // Remove default from all roles
        Role::where('is_default', true)->update(['is_default' => false]);

        // Set this role as default
        $role->update(['is_default' => true]);

        return back()->with('success', 'Rôle par défaut mis à jour.');
    }
}
