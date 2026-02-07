<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, fn ($query, $search) =>
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
            )
            ->when($request->role, fn ($query, $role) =>
                $query->whereHas('roles', fn ($q) => $q->where('slug', $role))
            )
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return Inertia::render('Users/Create', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,slug'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (!empty($validated['roles'])) {
            $roleIds = Role::whereIn('slug', $validated['roles'])->pluck('id');
            $user->roles()->sync($roleIds);
        }

        return redirect()->route('cambo.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles');

        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('roles');

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,slug'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if (isset($validated['roles'])) {
            $roleIds = Role::whereIn('slug', $validated['roles'])->pluck('id');
            $user->roles()->sync($roleIds);
        }

        return redirect()->route('cambo.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('cambo.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
