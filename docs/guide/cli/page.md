# cambo:page Command

The `cambo:page` command generates Vue page components with the AdminLayout structure, making it easy to create new admin panel pages.

## Basic Usage

```bash
php artisan cambo:page Reports/Analytics
```

This creates a page at `resources/js/Pages/Reports/Analytics.vue` with the AdminLayout wrapper and PageHeader component.

## Command Signature

```bash
php artisan cambo:page
    {name : The name of the page (e.g., Reports/Analytics)}
    {--title= : Page title}
    {--subtitle= : Page subtitle}
    {--with-card : Include a Card component}
    {--with-form : Include a Form structure}
    {--with-table : Include a Table structure}
    {--force : Overwrite existing file}
```

## Options

### `name` (Required)

The page name and path. Use forward slashes for nested directories.

```bash
# Simple page
php artisan cambo:page Dashboard

# Nested page
php artisan cambo:page Settings/Profile

# Deep nesting
php artisan cambo:page Admin/Reports/Sales/Monthly
```

### `--title`

Set a custom page title. If not provided, the title is derived from the page name using Laravel's `Str::headline()`.

```bash
php artisan cambo:page Reports/Analytics --title="Analytics Dashboard"
```

### `--subtitle`

Add a subtitle below the main title.

```bash
php artisan cambo:page Reports/Analytics --title="Analytics" --subtitle="View your site statistics"
```

### `--with-card`

Include a Card component wrapper for the page content.

```bash
php artisan cambo:page Settings/Profile --with-card
```

### `--with-form`

Include a form structure with Form, FormGroup, Input, and Button components.

```bash
php artisan cambo:page Settings/Profile --with-form
```

### `--with-table`

Include a table structure with Table, TableHead, TableBody, TableRow, TableCell, and Pagination components.

```bash
php artisan cambo:page Reports/Users --with-table
```

### `--force`

Overwrite an existing page file.

```bash
php artisan cambo:page Dashboard --force
```

## Examples

### Basic Page

```bash
php artisan cambo:page About
```

**Generated:** `resources/js/Pages/About.vue`

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'

defineProps({
    // Add props here
})
</script>

<template>
    <AdminLayout title="About">
        <PageHeader
            title="About"
        />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400">
                Page content goes here...
            </p>
        </div>
    </AdminLayout>
</template>
```

### Page with Card

```bash
php artisan cambo:page Settings/General --title="General Settings" --with-card
```

**Generated:** `resources/js/Pages/Settings/General.vue`

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'

defineProps({
    // Add props here
})
</script>

<template>
    <AdminLayout title="General Settings">
        <PageHeader
            title="General Settings"
        />

        <Card>
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-400">
                    Page content goes here...
                </p>
            </div>
        </Card>
    </AdminLayout>
</template>
```

### Page with Form

```bash
php artisan cambo:page Settings/Profile --title="Profile Settings" --subtitle="Update your profile information" --with-card --with-form
```

**Generated:** `resources/js/Pages/Settings/Profile.vue`

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Button from '@/Components/UI/Button.vue'

defineProps({
    // Add props here
})
</script>

<template>
    <AdminLayout title="Profile Settings">
        <PageHeader
            title="Profile Settings"
            subtitle="Update your profile information"
        />

        <Card>
            <Form class="p-6 space-y-6">
                <FormGroup label="Field Name" hint="Enter a value">
                    <Input placeholder="Enter value..." />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary">Cancel</Button>
                    <Button variant="primary">Save</Button>
                </div>
            </Form>
        </Card>
    </AdminLayout>
</template>
```

### Page with Table

```bash
php artisan cambo:page Reports/UserList --title="User Report" --with-card --with-table
```

**Generated:** `resources/js/Pages/Reports/UserList.vue`

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import Pagination from '@/Components/Data/Pagination.vue'

defineProps({
    // Add props here
})
</script>

<template>
    <AdminLayout title="User Report">
        <PageHeader
            title="User Report"
        />

        <Card>
            <Table>
                <TableHead>
                    <TableCell header>ID</TableCell>
                    <TableCell header>Name</TableCell>
                    <TableCell header>Status</TableCell>
                    <TableCell header class="text-right">Actions</TableCell>
                </TableHead>
                <TableBody>
                    <TableRow>
                        <TableCell>1</TableCell>
                        <TableCell>Example Item</TableCell>
                        <TableCell>Active</TableCell>
                        <TableCell class="text-right">-</TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <!-- Pagination goes here -->
            </div>
        </Card>
    </AdminLayout>
</template>
```

## Directory Structure

Pages are organized in the `resources/js/Pages/` directory:

```
resources/js/Pages/
├── Auth/
│   ├── Login.vue
│   └── Register.vue
├── Dashboard.vue
├── Reports/
│   ├── Analytics.vue
│   └── UserList.vue
├── Settings/
│   ├── General.vue
│   └── Profile.vue
└── Users/
    ├── Index.vue
    ├── Create.vue
    ├── Edit.vue
    └── Show.vue
```

## Adding Routes

After creating a page, add a route in your controller or routes file:

### Using a Controller

```php
// app/Http/Controllers/ReportController.php
use Inertia\Inertia;

class ReportController extends Controller
{
    public function analytics()
    {
        return Inertia::render('Reports/Analytics', [
            'stats' => $this->getStats(),
        ]);
    }
}

// routes/web.php
Route::get('/reports/analytics', [ReportController::class, 'analytics'])
    ->name('reports.analytics');
```

### Using Route Closures

```php
// routes/web.php
use Inertia\Inertia;

Route::get('/about', fn () => Inertia::render('About'))->name('about');
```

## Customizing Generated Pages

### Adding Props

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
})
</script>
```

### Adding Reactive State

```vue
<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'

const props = defineProps({
    users: Object,
})

const search = ref('')
const filteredUsers = computed(() => {
    if (!search.value) return props.users.data
    return props.users.data.filter(user =>
        user.name.toLowerCase().includes(search.value.toLowerCase())
    )
})
</script>
```

### Adding Actions to PageHeader

```vue
<template>
    <AdminLayout title="Users">
        <PageHeader title="Users" subtitle="Manage your users">
            <template #actions>
                <Button variant="secondary" :icon-left="DownloadIcon">
                    Export
                </Button>
                <Button variant="primary" :icon-left="PlusIcon" href="/users/create">
                    Add User
                </Button>
            </template>
        </PageHeader>

        <!-- Content -->
    </AdminLayout>
</template>
```

## Page Templates Reference

### Form Page Template

Complete form page with validation and submission:

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Textarea from '@/Components/Form/Textarea.vue'
import Select from '@/Components/Form/Select.vue'
import Toggle from '@/Components/Form/Toggle.vue'
import Button from '@/Components/UI/Button.vue'

const form = useForm({
    name: '',
    email: '',
    role: '',
    bio: '',
    active: true,
})

const submit = () => {
    form.post('/users')
}
</script>

<template>
    <AdminLayout title="Create User">
        <PageHeader title="Create User" :back-url="'/users'" />

        <Card>
            <Form @submit="submit" class="p-6 space-y-6">
                <FormGroup label="Name" :error="form.errors.name" required>
                    <Input v-model="form.name" placeholder="Enter name" />
                </FormGroup>

                <FormGroup label="Email" :error="form.errors.email" required>
                    <Input v-model="form.email" type="email" placeholder="Enter email" />
                </FormGroup>

                <FormGroup label="Role" :error="form.errors.role">
                    <Select v-model="form.role" :options="[
                        { value: 'admin', label: 'Administrator' },
                        { value: 'user', label: 'User' },
                    ]" />
                </FormGroup>

                <FormGroup label="Bio" :error="form.errors.bio">
                    <Textarea v-model="form.bio" rows="4" />
                </FormGroup>

                <FormGroup label="Active">
                    <Toggle v-model="form.active" />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary" href="/users">Cancel</Button>
                    <Button type="submit" variant="primary" :loading="form.processing">
                        Create User
                    </Button>
                </div>
            </Form>
        </Card>
    </AdminLayout>
</template>
```

### List Page Template

Complete list page with search, sorting, and pagination:

```vue
<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { useDebounceFn } from '@vueuse/core'

const props = defineProps({
    users: Object,
    filters: Object,
})

const search = ref(props.filters?.search || '')

const doSearch = useDebounceFn(() => {
    router.get('/users', { search: search.value }, { preserveState: true })
}, 300)

watch(search, doSearch)
</script>

<template>
    <AdminLayout title="Users">
        <PageHeader title="Users" subtitle="Manage your users">
            <template #actions>
                <Button variant="primary" :icon-left="PlusIcon" href="/users/create">
                    Add User
                </Button>
            </template>
        </PageHeader>

        <Card>
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <SearchInput v-model="search" placeholder="Search users..." />
            </div>

            <Table>
                <TableHead>
                    <TableCell header>Name</TableCell>
                    <TableCell header>Email</TableCell>
                    <TableCell header>Role</TableCell>
                    <TableCell header>Status</TableCell>
                    <TableCell header class="text-right">Actions</TableCell>
                </TableHead>
                <TableBody>
                    <TableRow v-for="user in users.data" :key="user.id">
                        <TableCell>{{ user.name }}</TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                        <TableCell>{{ user.role }}</TableCell>
                        <TableCell>
                            <Badge :variant="user.active ? 'success' : 'secondary'">
                                {{ user.active ? 'Active' : 'Inactive' }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="sm" :icon-left="PencilIcon"
                                    :href="`/users/${user.id}/edit`" />
                                <Button variant="ghost" size="sm" :icon-left="TrashIcon"
                                    class="text-red-600" @click="confirmDelete(user)" />
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <Pagination :data="users" />
            </div>
        </Card>
    </AdminLayout>
</template>
```

## See Also

- [cambo:crud](./crud.md) - Generate complete CRUD modules
- [cambo:component](./component.md) - Generate Vue components
- [cambo:install](./install.md) - Initial installation
