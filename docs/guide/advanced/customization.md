# Customization

This guide covers how to customize CamboAdmin components, layouts, and styling to match your project's requirements.

## Component Customization

### Overriding Published Components

After installation, components are published to `resources/js/Components/`. You can freely modify these files.

```bash
# View published components
ls resources/js/Components/
```

### Creating Custom Variants

Extend existing components with new variants:

```vue
<!-- resources/js/Components/UI/Button.vue -->
<script setup>
const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => [
            'primary',
            'secondary',
            'danger',
            'success',
            'warning',
            'ghost',
            'outline',
            // Add your custom variants
            'brand',
            'accent',
        ].includes(value),
    },
})

const variantClasses = {
    primary: 'bg-indigo-600 text-white hover:bg-indigo-700',
    secondary: 'bg-gray-200 text-gray-900 hover:bg-gray-300',
    danger: 'bg-red-600 text-white hover:bg-red-700',
    success: 'bg-green-600 text-white hover:bg-green-700',
    warning: 'bg-yellow-500 text-white hover:bg-yellow-600',
    ghost: 'bg-transparent text-gray-700 hover:bg-gray-100',
    outline: 'border border-gray-300 text-gray-700 hover:bg-gray-50',
    // Custom variants
    brand: 'bg-purple-600 text-white hover:bg-purple-700',
    accent: 'bg-teal-500 text-white hover:bg-teal-600',
}
</script>
```

### Component Wrapper Pattern

Create wrapper components that extend base functionality:

```vue
<!-- resources/js/Components/Custom/PrimaryButton.vue -->
<script setup>
import Button from '@/Components/UI/Button.vue'

const props = defineProps({
    loading: Boolean,
})
</script>

<template>
    <Button
        variant="primary"
        :loading="loading"
        class="font-semibold tracking-wide"
        v-bind="$attrs"
    >
        <slot />
    </Button>
</template>
```

### Composable Overrides

Override or extend composables in `resources/js/Composables/`:

```javascript
// resources/js/Composables/useNotification.js
import { ref } from 'vue'

const notifications = ref([])

export function useNotification() {
    const add = (notification) => {
        const id = Date.now()
        notifications.value.push({
            id,
            ...notification,
            // Custom default duration
            duration: notification.duration ?? 5000,
        })

        // Auto-remove after duration
        if (notification.duration !== 0) {
            setTimeout(() => remove(id), notification.duration ?? 5000)
        }

        return id
    }

    const remove = (id) => {
        const index = notifications.value.findIndex((n) => n.id === id)
        if (index !== -1) {
            notifications.value.splice(index, 1)
        }
    }

    // Custom helper methods
    const success = (message, options = {}) => {
        return add({ type: 'success', message, ...options })
    }

    const error = (message, options = {}) => {
        return add({ type: 'error', message, duration: 0, ...options })
    }

    const warning = (message, options = {}) => {
        return add({ type: 'warning', message, ...options })
    }

    const info = (message, options = {}) => {
        return add({ type: 'info', message, ...options })
    }

    return {
        notifications,
        add,
        remove,
        success,
        error,
        warning,
        info,
    }
}
```

## Layout Customization

### AdminLayout Structure

The main layout is in `resources/js/Components/Layout/AdminLayout.vue`:

```vue
<script setup>
import { ref, computed } from 'vue'
import Sidebar from './Sidebar.vue'
import Navbar from './Navbar.vue'
import Footer from './Footer.vue'

const props = defineProps({
    title: String,
})

const sidebarCollapsed = ref(false)
const sidebarOpen = ref(false) // Mobile

const mainClass = computed(() => ({
    'ml-64': !sidebarCollapsed.value,
    'ml-20': sidebarCollapsed.value,
}))
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <Sidebar
            :collapsed="sidebarCollapsed"
            :open="sidebarOpen"
            @toggle="sidebarCollapsed = !sidebarCollapsed"
            @close="sidebarOpen = false"
        />

        <div :class="mainClass" class="transition-all duration-300">
            <Navbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />

            <main class="p-6">
                <slot />
            </main>

            <Footer />
        </div>
    </div>
</template>
```

### Custom Sidebar Navigation

Modify the sidebar menu in `Sidebar.vue`:

```vue
<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import {
    HomeIcon,
    UsersIcon,
    CogIcon,
    ChartBarIcon,
    // Add more icons
} from '@heroicons/vue/24/outline'

const page = usePage()
const user = computed(() => page.props.auth?.user)

const navigation = computed(() => [
    {
        name: 'Dashboard',
        href: '/dashboard',
        icon: HomeIcon,
        current: route().current('dashboard'),
    },
    {
        name: 'Users',
        href: '/users',
        icon: UsersIcon,
        current: route().current('users.*'),
        // Permission-based visibility
        visible: user.value?.can?.includes('users.view'),
    },
    {
        name: 'Reports',
        icon: ChartBarIcon,
        // Nested navigation
        children: [
            { name: 'Analytics', href: '/reports/analytics' },
            { name: 'Sales', href: '/reports/sales' },
            { name: 'Users', href: '/reports/users' },
        ],
    },
    {
        name: 'Settings',
        href: '/settings',
        icon: CogIcon,
        current: route().current('settings.*'),
    },
])

// Filter visible items
const visibleNavigation = computed(() =>
    navigation.value.filter((item) => item.visible !== false)
)
</script>
```

### Multiple Layouts

Create different layouts for different sections:

```vue
<!-- resources/js/Components/Layout/AuthLayout.vue -->
<script setup>
defineProps({
    title: String,
})
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="max-w-md w-full space-y-8 p-8">
            <div class="text-center">
                <img src="/logo.svg" alt="Logo" class="mx-auto h-12" />
                <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ title }}
                </h2>
            </div>

            <slot />
        </div>
    </div>
</template>
```

```vue
<!-- resources/js/Components/Layout/PublicLayout.vue -->
<script setup>
import PublicNavbar from './PublicNavbar.vue'
import PublicFooter from './PublicFooter.vue'
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <PublicNavbar />

        <main class="flex-grow">
            <slot />
        </main>

        <PublicFooter />
    </div>
</template>
```

## Styling Customization

### Tailwind Configuration

Customize your theme in `tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // Custom brand colors
                brand: {
                    50: '#f5f3ff',
                    100: '#ede9fe',
                    200: '#ddd6fe',
                    300: '#c4b5fd',
                    400: '#a78bfa',
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                    800: '#5b21b6',
                    900: '#4c1d95',
                },
                // Override default colors
                primary: {
                    DEFAULT: '#6366f1',
                    50: '#eef2ff',
                    // ... other shades
                },
            },
            fontFamily: {
                sans: ['Inter var', 'system-ui', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
```

### CSS Variables

Use CSS variables for dynamic theming in `resources/css/app.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    :root {
        --color-primary: 99 102 241;
        --color-secondary: 107 114 128;
        --color-success: 34 197 94;
        --color-danger: 239 68 68;
        --color-warning: 234 179 8;
        --color-info: 59 130 246;

        --sidebar-width: 256px;
        --sidebar-collapsed-width: 80px;
        --navbar-height: 64px;

        --border-radius: 0.5rem;
        --transition-duration: 150ms;
    }

    .dark {
        --color-primary: 129 140 248;
        --color-secondary: 156 163 175;
    }
}

@layer components {
    .btn-primary {
        @apply bg-[rgb(var(--color-primary))] text-white;
        @apply hover:bg-[rgb(var(--color-primary)/0.9)];
        @apply rounded-[var(--border-radius)];
        @apply transition-colors duration-[var(--transition-duration)];
    }

    .card {
        @apply bg-white dark:bg-gray-800;
        @apply rounded-[var(--border-radius)];
        @apply shadow-soft;
    }
}
```

### Dark Mode

Implement dark mode with the `useTheme` composable:

```javascript
// resources/js/Composables/useTheme.js
import { ref, watch, onMounted } from 'vue'

const theme = ref('system') // 'light', 'dark', 'system'

export function useTheme() {
    const setTheme = (newTheme) => {
        theme.value = newTheme
        localStorage.setItem('theme', newTheme)
        applyTheme()
    }

    const applyTheme = () => {
        const isDark =
            theme.value === 'dark' ||
            (theme.value === 'system' &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)

        document.documentElement.classList.toggle('dark', isDark)
    }

    const toggleTheme = () => {
        const themes = ['light', 'dark', 'system']
        const currentIndex = themes.indexOf(theme.value)
        const nextIndex = (currentIndex + 1) % themes.length
        setTheme(themes[nextIndex])
    }

    onMounted(() => {
        // Load saved theme
        const savedTheme = localStorage.getItem('theme')
        if (savedTheme) {
            theme.value = savedTheme
        }
        applyTheme()

        // Watch for system theme changes
        window
            .matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', applyTheme)
    })

    return {
        theme,
        setTheme,
        toggleTheme,
    }
}
```

## Configuration Customization

### CamboAdmin Config

Customize the package in `config/cambo-admin.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Settings
    |--------------------------------------------------------------------------
    */
    'name' => env('CAMBO_ADMIN_NAME', 'Admin Panel'),
    'logo' => env('CAMBO_ADMIN_LOGO', '/images/logo.svg'),
    'favicon' => env('CAMBO_ADMIN_FAVICON', '/favicon.ico'),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */
    'prefix' => env('CAMBO_ADMIN_PREFIX', 'admin'),
    'middleware' => ['web', 'auth', 'verified'],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */
    'models' => [
        'user' => App\Models\User::class,
        'role' => App\Models\Role::class,
        'permission' => App\Models\Permission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Features/Modules
    |--------------------------------------------------------------------------
    */
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
        'import-export' => false,
        'i18n' => false,
        'themes' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'per_page' => 25,
        'per_page_options' => [10, 25, 50, 100],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'disk' => 'public',
        'path' => 'uploads',
        'max_size' => 10240, // KB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'default_widgets' => [
            'stats',
            'recent-users',
            'activity-chart',
        ],
    ],
];
```

### Environment Variables

Use `.env` for environment-specific settings:

```env
CAMBO_ADMIN_NAME="My Admin Panel"
CAMBO_ADMIN_PREFIX=admin
CAMBO_ADMIN_LOGO=/images/my-logo.svg
```

## Page Customization

### Custom Dashboard

Create a custom dashboard page:

```vue
<!-- resources/js/Pages/Dashboard.vue -->
<script setup>
import { computed } from 'vue'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import StatsCard from '@/Components/Widgets/StatsCard.vue'
import RecentActivity from '@/Components/Widgets/RecentActivity.vue'
import Chart from '@/Components/Charts/Chart.vue'

const props = defineProps({
    stats: Object,
    recentActivity: Array,
    chartData: Object,
})
</script>

<template>
    <AdminLayout title="Dashboard">
        <PageHeader
            title="Dashboard"
            subtitle="Welcome back! Here's what's happening."
        />

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <StatsCard
                title="Total Users"
                :value="stats.users"
                :change="stats.usersChange"
                icon="users"
            />
            <StatsCard
                title="Revenue"
                :value="stats.revenue"
                :change="stats.revenueChange"
                icon="currency"
                format="currency"
            />
            <StatsCard
                title="Orders"
                :value="stats.orders"
                :change="stats.ordersChange"
                icon="shopping-cart"
            />
            <StatsCard
                title="Conversion"
                :value="stats.conversion"
                :change="stats.conversionChange"
                icon="trending-up"
                format="percentage"
            />
        </div>

        <!-- Charts and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <Card class="lg:col-span-2">
                <template #header>
                    <h3 class="text-lg font-semibold">Revenue Overview</h3>
                </template>
                <Chart :data="chartData" type="line" />
            </Card>

            <Card>
                <template #header>
                    <h3 class="text-lg font-semibold">Recent Activity</h3>
                </template>
                <RecentActivity :items="recentActivity" />
            </Card>
        </div>
    </AdminLayout>
</template>
```

### Custom Error Pages

Create custom error pages:

```vue
<!-- resources/js/Pages/Errors/404.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'
import Button from '@/Components/UI/Button.vue'
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="text-center">
            <h1 class="text-9xl font-bold text-gray-200 dark:text-gray-700">404</h1>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-4">
                Page not found
            </p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <div class="mt-8">
                <Button variant="primary" as="Link" href="/">
                    Go back home
                </Button>
            </div>
        </div>
    </div>
</template>
```

## See Also

- [Extending](/guide/advanced/extending.md) - Adding new features
- [Security](/guide/advanced/security.md) - Security best practices
- [Component API](/components/) - Component documentation
