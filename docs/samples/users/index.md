# User List Page

A data table with search, filters, sorting, pagination, and bulk actions for managing users.

## Preview

<div style="background: #f3f4f6; padding: 20px; border-radius: 12px;">
  <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="padding: 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
      <div style="display: flex; gap: 12px; align-items: center;">
        <input type="text" placeholder="Search users..." style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; width: 250px;" />
        <select style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
          <option>All Roles</option>
        </select>
        <select style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
          <option>All Status</option>
        </select>
      </div>
      <button style="padding: 8px 16px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: 500;">Add User</button>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #f9fafb;">
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px;"><input type="checkbox" /></th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px;">User</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px;">Role</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px;">Status</th>
          <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 14px;">Created</th>
          <th style="padding: 12px 16px; text-align: right; font-weight: 600; font-size: 14px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: 1px solid #e5e7eb;">
          <td style="padding: 12px 16px;"><input type="checkbox" /></td>
          <td style="padding: 12px 16px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <div style="width: 32px; height: 32px; background: #4f46e5; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px;">JD</div>
              <div>
                <div style="font-weight: 500;">John Doe</div>
                <div style="font-size: 12px; color: #6b7280;">john@example.com</div>
              </div>
            </div>
          </td>
          <td style="padding: 12px 16px;"><span style="background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 4px; font-size: 12px;">Admin</span></td>
          <td style="padding: 12px 16px;"><span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 9999px; font-size: 12px;">Active</span></td>
          <td style="padding: 12px 16px; font-size: 14px; color: #6b7280;">Feb 7, 2024</td>
          <td style="padding: 12px 16px; text-align: right;">
            <button style="padding: 4px 8px; border: none; background: none; cursor: pointer;">...</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

## Full Code

```vue
<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import {
    AdminLayout,
    PageHeader,
    Card,
    DataTable,
    SearchInput,
    Select,
    Button,
    ButtonGroup,
    Avatar,
    Badge,
    Dropdown,
    DropdownItem,
    DropdownDivider,
    ConfirmModal,
    Modal,
    Toast
} from '@cambosoftware/cambo-admin'
import {
    PlusIcon,
    PencilIcon,
    TrashIcon,
    EyeIcon,
    EllipsisVerticalIcon,
    ArrowDownTrayIcon,
    FunnelIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    users: Object,
    roles: Array,
    filters: Object
})

// Search and filters
const search = ref(props.filters.search || '')
const roleFilter = ref(props.filters.role || '')
const statusFilter = ref(props.filters.status || '')

// Bulk selection
const selectedUsers = ref([])
const showDeleteConfirm = ref(false)
const userToDelete = ref(null)

// Status options
const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'pending', label: 'Pending' }
]

// Role options
const roleOptions = computed(() => [
    { value: '', label: 'All Roles' },
    ...props.roles.map(r => ({ value: r.id, label: r.name }))
])

// Table columns
const columns = [
    { key: 'name', label: 'User', sortable: true },
    { key: 'role', label: 'Role', sortable: true },
    { key: 'status', label: 'Status', sortable: true },
    { key: 'created_at', label: 'Created', sortable: true },
    { key: 'actions', label: '', align: 'right' }
]

// Bulk actions
const bulkActions = [
    { key: 'activate', label: 'Activate', action: () => bulkAction('activate') },
    { key: 'deactivate', label: 'Deactivate', action: () => bulkAction('deactivate') },
    { key: 'delete', label: 'Delete', variant: 'danger', action: () => confirmBulkDelete() }
]

// Watch for filter changes
watch([search, roleFilter, statusFilter], () => {
    applyFilters()
}, { debounce: 300 })

const applyFilters = () => {
    router.get(route('users.index'), {
        search: search.value || undefined,
        role: roleFilter.value || undefined,
        status: statusFilter.value || undefined
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

const confirmDelete = (user) => {
    userToDelete.value = user
    showDeleteConfirm.value = true
}

const deleteUser = () => {
    router.delete(route('users.destroy', userToDelete.value.id), {
        onSuccess: () => {
            showDeleteConfirm.value = false
            userToDelete.value = null
        }
    })
}

const bulkAction = (action) => {
    router.post(route('users.bulk-action'), {
        action,
        ids: selectedUsers.value
    }, {
        onSuccess: () => {
            selectedUsers.value = []
        }
    })
}

const confirmBulkDelete = () => {
    if (confirm(`Are you sure you want to delete ${selectedUsers.value.length} users?`)) {
        bulkAction('delete')
    }
}

const exportUsers = () => {
    window.location.href = route('users.export', {
        search: search.value,
        role: roleFilter.value,
        status: statusFilter.value
    })
}

const getStatusVariant = (status) => {
    const variants = {
        active: 'success',
        inactive: 'danger',
        pending: 'warning'
    }
    return variants[status] || 'default'
}
</script>

<template>
    <AdminLayout title="Users">
        <PageHeader
            title="Users"
            subtitle="Manage your team members and their account permissions."
        >
            <template #actions>
                <Button variant="outline" @click="exportUsers">
                    <ArrowDownTrayIcon class="h-4 w-4 mr-2" />
                    Export
                </Button>
                <Button variant="primary" :href="route('users.create')">
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add User
                </Button>
            </template>
        </PageHeader>

        <Card>
            <!-- Filters -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex flex-wrap gap-3 items-center">
                        <SearchInput
                            v-model="search"
                            placeholder="Search users..."
                            class="w-64"
                        />
                        <Select
                            v-model="roleFilter"
                            :options="roleOptions"
                            class="w-40"
                        />
                        <Select
                            v-model="statusFilter"
                            :options="statusOptions"
                            class="w-40"
                        />
                    </div>

                    <!-- Bulk Actions -->
                    <div v-if="selectedUsers.length > 0" class="flex items-center gap-3">
                        <span class="text-sm text-gray-600">
                            {{ selectedUsers.length }} selected
                        </span>
                        <ButtonGroup>
                            <Button
                                v-for="action in bulkActions"
                                :key="action.key"
                                size="sm"
                                :variant="action.variant || 'outline'"
                                @click="action.action"
                            >
                                {{ action.label }}
                            </Button>
                        </ButtonGroup>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <DataTable
                :resource="users"
                :columns="columns"
                v-model:selected="selectedUsers"
                selectable
                hoverable
                @sort="applyFilters"
            >
                <!-- User Cell -->
                <template #cell-name="{ row }">
                    <div class="flex items-center gap-3">
                        <Avatar
                            :src="row.avatar_url"
                            :name="row.name"
                            size="sm"
                        />
                        <div>
                            <p class="font-medium text-gray-900">{{ row.name }}</p>
                            <p class="text-sm text-gray-500">{{ row.email }}</p>
                        </div>
                    </div>
                </template>

                <!-- Role Cell -->
                <template #cell-role="{ row }">
                    <Badge variant="info" outline>
                        {{ row.role?.name || 'No Role' }}
                    </Badge>
                </template>

                <!-- Status Cell -->
                <template #cell-status="{ value }">
                    <Badge :variant="getStatusVariant(value)" pill>
                        {{ value }}
                    </Badge>
                </template>

                <!-- Created Cell -->
                <template #cell-created_at="{ value }">
                    <span class="text-gray-500">{{ value }}</span>
                </template>

                <!-- Actions Cell -->
                <template #cell-actions="{ row }">
                    <Dropdown align="right">
                        <template #trigger>
                            <Button variant="ghost" size="sm" icon-only>
                                <EllipsisVerticalIcon class="h-5 w-5" />
                            </Button>
                        </template>

                        <DropdownItem :icon="EyeIcon" :href="route('users.show', row.id)">
                            View
                        </DropdownItem>
                        <DropdownItem :icon="PencilIcon" :href="route('users.edit', row.id)">
                            Edit
                        </DropdownItem>
                        <DropdownDivider />
                        <DropdownItem
                            :icon="TrashIcon"
                            variant="danger"
                            @click="confirmDelete(row)"
                        >
                            Delete
                        </DropdownItem>
                    </Dropdown>
                </template>

                <!-- Empty State -->
                <template #empty>
                    <div class="text-center py-12">
                        <UsersIcon class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No users</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started by creating a new user.
                        </p>
                        <div class="mt-6">
                            <Button variant="primary" :href="route('users.create')">
                                <PlusIcon class="h-4 w-4 mr-2" />
                                Add User
                            </Button>
                        </div>
                    </div>
                </template>
            </DataTable>
        </Card>

        <!-- Delete Confirmation Modal -->
        <ConfirmModal
            v-model="showDeleteConfirm"
            title="Delete User"
            :message="`Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone.`"
            confirm-text="Delete"
            confirm-variant="danger"
            @confirm="deleteUser"
        />
    </AdminLayout>
</template>
```

## Laravel Controller

```php
// app/Http/Controllers/UserController.php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                'search' => AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%")
                          ->orWhere('email', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::exact('role', 'role_id'),
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts(['name', 'email', 'created_at', 'status'])
            ->with('role')
            ->latest()
            ->paginate($request->get('per_page', 15))
            ->withQueryString();

        return inertia('Users/Index', [
            'users' => [
                'data' => $users->items(),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ],
            'roles' => Role::all(['id', 'name']),
            'filters' => $request->only(['search', 'role', 'status']),
        ]);
    }

    public function create()
    {
        return inertia('Users/Create', [
            'roles' => Role::all(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive,pending',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return inertia('Users/Edit', [
            'user' => $user->load('role'),
            'roles' => Role::all(['id', 'name']),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive,pending',
        ]);

        if ($validated['password']) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $validated['ids']);

        switch ($validated['action']) {
            case 'activate':
                $users->update(['status' => 'active']);
                break;
            case 'deactivate':
                $users->update(['status' => 'inactive']);
                break;
            case 'delete':
                $users->delete();
                break;
        }

        return back()->with('success', 'Bulk action completed.');
    }

    public function export(Request $request)
    {
        // Export logic here
        return response()->download('users.csv');
    }
}
```

## Routes

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
});
```
