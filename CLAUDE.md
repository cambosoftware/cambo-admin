# CLAUDE.md

This file provides guidance to AI assistants (Claude, GPT, etc.) when working with CamboAdmin.

## What is CamboAdmin?

CamboAdmin is a complete Laravel admin panel package with 150+ Vue.js components. It provides authentication, roles & permissions, CRUD generator, and a full UI component library.

- **Package**: `cambosoftware/cambo-admin`
- **Documentation**: https://cambo-admin.cambosoftware.com
- **GitHub**: https://github.com/cambosoftware/cambo-admin
- **Packagist**: https://packagist.org/packages/cambosoftware/cambo-admin

## Requirements

- PHP 8.2+
- Laravel 11+ or 12+
- Node.js 18+
- Composer 2+

## Quick Installation

```bash
# Create new Laravel project (if needed)
composer create-project laravel/laravel my-admin
cd my-admin

# Install CamboAdmin
composer require cambosoftware/cambo-admin

# Run the installer (full installation recommended)
php artisan cambo:install --full

# Install frontend dependencies
npm install
npm run build

# Start the server
php artisan serve
```

Access the admin panel at: `http://localhost:8000/admin`

## CLI Commands

### Generate CRUD

```bash
php artisan cambo:crud Product --fields="name:string,price:decimal,active:boolean"
```

This generates:
- Migration file
- Model with fillable attributes
- Controller with index, create, store, show, edit, update, destroy methods
- 4 Vue pages: Index.vue, Create.vue, Edit.vue, Show.vue
- Routes automatically registered

### Generate Page

```bash
php artisan cambo:page Reports/Analytics --title="Analytics Dashboard"
```

### Generate Component

```bash
php artisan cambo:component Charts/RevenueChart --with-props --with-emits
```

### Add Module

```bash
php artisan cambo:add notifications
php artisan cambo:add activity-log
php artisan cambo:add media
```

## Available Modules

| Module | Description |
|--------|-------------|
| `auth` | Authentication (login, register, 2FA, password reset) |
| `users` | User management CRUD |
| `roles` | Role management |
| `permissions` | Permission management |
| `notifications` | Notification center |
| `activity-log` | Activity tracking |
| `dashboard` | Customizable dashboard with widgets |
| `media` | File manager |
| `settings` | Dynamic settings |
| `import-export` | CSV, Excel, PDF export/import |
| `i18n` | Multi-language support |
| `themes` | Theme customization |

## Component Categories

### Layout (8 components)
`AdminLayout`, `Sidebar`, `SidebarItem`, `SidebarDivider`, `Navbar`, `Breadcrumb`, `PageHeader`, `Container`

### UI (12 components)
`Button`, `ButtonGroup`, `IconButton`, `Badge`, `Avatar`, `AvatarGroup`, `Icon`, `Spinner`, `Skeleton`, `Tooltip`, `Divider`, `AppLink`

### Forms - Basic (14 components)
`Form`, `FormGroup`, `Input`, `Textarea`, `Select`, `SelectSearch`, `SelectMultiple`, `Checkbox`, `CheckboxGroup`, `Radio`, `RadioGroup`, `RadioCards`, `Switch`, `Toggle`

### Forms - Advanced (20 components)
`DatePicker`, `DateRangePicker`, `TimePicker`, `DateTimePicker`, `ColorPicker`, `FilePicker`, `ImagePicker`, `FileDropzone`, `RichTextEditor`, `MarkdownEditor`, `CodeEditor`, `TagInput`, `SliderInput`, `RangeInput`, `RatingInput`, `PasswordInput`, `SearchInput`, `PhoneInput`, `CurrencyInput`, `NumberInput`

### Data Display (38 components)
`DataTable`, `Table`, `TableHead`, `TableBody`, `TableRow`, `TableCell`, `SortableHeader`, `Pagination`, `List`, `ListItem`, `DescriptionList`, `Tree`, `Timeline`, `Calendar`, `KanbanBoard`, plus formatters

### Charts (9 components)
`Chart`, `LineChart`, `AreaChart`, `BarChart`, `DonutChart`, `PieChart`, `StatCard`, `StatGrid`, `MiniChart`

### Overlays (8 components)
`Modal`, `ConfirmModal`, `Drawer`, `Dropdown`, `DropdownItem`, `DropdownDivider`, `Popover`, `ContextMenu`

### Feedback (6 components)
`Alert`, `Toast`, `ToastContainer`, `ProgressBar`, `EmptyState`, `ErrorState`

### Containers (8 components)
`Card`, `CardGrid`, `Accordion`, `AccordionItem`, `Tabs`, `Tab`, `Collapse`, `Panel`

### Navigation (5 components)
`NavLink`, `NavGroup`, `StepWizard`, `BackButton`, `CommandPalette`

### Utilities (7 components)
`CopyButton`, `ClickToCopy`, `ExternalLink`, `Highlight`, `RelativeTime`, `CountUp`, `Kbd`

## Common Patterns

### Basic Page Structure

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import Card from '@/Components/Containers/Card.vue'
import Button from '@/Components/UI/Button.vue'

defineProps({
  title: String,
  data: Object,
})
</script>

<template>
  <AdminLayout :title="title">
    <Card>
      <template #header>
        <h2>{{ title }}</h2>
      </template>

      <!-- Content here -->

      <template #footer>
        <Button variant="primary">Save</Button>
      </template>
    </Card>
  </AdminLayout>
</template>
```

### DataTable with Server-Side Pagination

```php
// Controller
use CamboSoftware\CamboAdmin\QueryBuilder\QueryBuilder;

public function index()
{
    return inertia('Users/Index', [
        'users' => QueryBuilder::for(User::class)
            ->columns(['id', 'name', 'email', 'created_at'])
            ->searchable(['name', 'email'])
            ->sortable(['name', 'created_at'])
            ->filterable(['role', 'status'])
            ->exportable(['csv', 'excel', 'pdf'])
            ->paginate(25)
    ]);
}
```

```vue
<!-- Vue Page -->
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import DataTable from '@/Components/Data/DataTable.vue'

defineProps({ users: Object })
</script>

<template>
  <AdminLayout title="Users">
    <DataTable
      :resource="users"
      :columns="[
        { key: 'name', label: 'Name', sortable: true },
        { key: 'email', label: 'Email', sortable: true },
        { key: 'created_at', label: 'Created', type: 'date' },
      ]"
    />
  </AdminLayout>
</template>
```

### Form with Validation

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Select from '@/Components/Form/Select.vue'
import Button from '@/Components/UI/Button.vue'

const form = useForm({
  name: '',
  email: '',
  role: '',
})

const submit = () => {
  form.post('/admin/users')
}
</script>

<template>
  <Form @submit="submit">
    <FormGroup label="Name" :error="form.errors.name">
      <Input v-model="form.name" required />
    </FormGroup>

    <FormGroup label="Email" :error="form.errors.email">
      <Input v-model="form.email" type="email" required />
    </FormGroup>

    <FormGroup label="Role" :error="form.errors.role">
      <Select
        v-model="form.role"
        :options="[
          { value: 'admin', label: 'Admin' },
          { value: 'user', label: 'User' },
        ]"
      />
    </FormGroup>

    <Button type="submit" :loading="form.processing">
      Create User
    </Button>
  </Form>
</template>
```

### Modal Confirmation

```vue
<script setup>
import { ref } from 'vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Button from '@/Components/UI/Button.vue'

const showDeleteModal = ref(false)

const deleteItem = () => {
  // Perform delete action
  showDeleteModal.value = false
}
</script>

<template>
  <Button variant="danger" @click="showDeleteModal = true">
    Delete
  </Button>

  <ConfirmModal
    v-model="showDeleteModal"
    title="Delete Item"
    message="Are you sure you want to delete this item?"
    confirm-text="Delete"
    variant="danger"
    @confirm="deleteItem"
  />
</template>
```

### Role-Based UI

```vue
<script setup>
import { usePage } from '@inertiajs/vue3'
import Button from '@/Components/UI/Button.vue'

const { props } = usePage()

// Check permission
const can = (permission) => {
  return props.auth.permissions?.includes(permission)
}
</script>

<template>
  <Button v-if="can('users.create')" @click="createUser">
    Create User
  </Button>

  <Button v-if="can('users.delete')" variant="danger" @click="deleteUser">
    Delete
  </Button>
</template>
```

## Configuration

Main config file: `config/cambo-admin.php`

```php
return [
    'modules' => [
        'auth' => true,
        'users' => true,
        'roles' => true,
        'permissions' => true,
        'notifications' => true,
        'activity-log' => true,
        'dashboard' => true,
        'media' => true,
        'settings' => true,
        'import-export' => true,
        'i18n' => true,
        'themes' => true,
    ],

    'routes' => [
        'prefix' => 'admin',
        'middleware' => ['web', 'auth', 'verified'],
    ],

    'appearance' => [
        'name' => 'My Admin',
        'logo' => null,
        'primary_color' => '#6366f1',
        'dark_mode' => 'auto',
    ],
];
```

## Middleware

### Check Role

```php
Route::middleware(['role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['role:admin,manager'])->group(function () {
    // Admin OR Manager can access
});
```

### Check Permission

```php
Route::middleware(['permission:users.create'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});
```

## HasRoles Trait

Add to your User model:

```php
use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

Available methods:

```php
$user->assignRole('admin');
$user->removeRole('admin');
$user->hasRole('admin');
$user->hasAnyRole(['admin', 'manager']);
$user->hasPermission('users.create');
$user->hasAnyPermission(['users.create', 'users.edit']);
$user->getAllPermissions();
```

## Development Guidelines (IMPORTANT)

When developing new pages or components, follow these rules to avoid common issues:

### URLs and Routes

```php
// WRONG - Named routes may not be defined
return redirect()->route('roles.index');

// CORRECT - Use direct URLs with /admin prefix
return redirect('/admin/roles');
```

### User Model References

```php
// WRONG - Will fail because User class doesn't exist in package namespace
public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class);
}

// CORRECT - Use config with fallback
public function users(): BelongsToMany
{
    $userModel = config('cambo-admin.models.user') ?? \App\Models\User::class;
    return $this->belongsToMany($userModel, 'user_role');
}
```

### Dropdown Components in Overflow Containers

When a dropdown might be inside a container with `overflow: hidden/auto/scroll`, use Teleport:

```vue
<Teleport to="body">
    <div v-if="open" class="fixed z-50" :style="dropdownStyle">
        <!-- dropdown content -->
    </div>
</Teleport>
```

### Checkbox with Array v-model

Checkbox supports both Boolean and Array modes:

```vue
<!-- Boolean mode -->
<Checkbox v-model="isActive" label="Active" />

<!-- Array mode for checkbox groups -->
<Checkbox
    v-for="perm in permissions"
    :key="perm.slug"
    v-model="selectedPermissions"
    :value="perm.slug"
    :label="perm.name"
/>
```

### Component Slots

Always check component source for supported slots. Common mistake:

```vue
<!-- WRONG - DescriptionList doesn't have #items slot -->
<DescriptionList>
    <template #items>...</template>
</DescriptionList>

<!-- CORRECT - Use default slot -->
<DescriptionList>
    <div class="py-3">...</div>
</DescriptionList>
```

### Dark Mode

Always add dark mode classes:

```vue
<!-- WRONG -->
<span class="text-gray-900">Text</span>

<!-- CORRECT -->
<span class="text-gray-900 dark:text-gray-100">Text</span>
```

### Permission Data Format

Permissions from the database are objects, not strings:

```javascript
// WRONG - Permissions are objects with slug property
const form = useForm({
    permissions: props.role.permissions  // [{id, name, slug}, ...]
})

// CORRECT - Extract slugs
const initialPermissions = (props.role.permissions || [])
    .map(p => typeof p === 'string' ? p : p.slug)

const form = useForm({
    permissions: initialPermissions  // ['perm-1', 'perm-2', ...]
})
```

## Full Documentation

For complete documentation with all component props, events, and slots, visit:
https://cambo-admin.cambosoftware.com

For AI-optimized documentation:
- Summary: https://cambo-admin.cambosoftware.com/llms.txt
- Full: https://cambo-admin.cambosoftware.com/llms-full.txt
