# Installation

This guide walks you through installing CamboAdmin in your Laravel application. The process takes just a few minutes and offers multiple installation modes to suit your needs.

## Requirements

Before installing, ensure your environment meets these requirements:

| Requirement | Minimum Version | Check Command |
|-------------|-----------------|---------------|
| **PHP** | 8.2+ | `php -v` |
| **Laravel** | 11.0+ or 12.0+ | `php artisan --version` |
| **Node.js** | 18.0+ | `node -v` |
| **npm** | 9.0+ | `npm -v` |
| **Composer** | 2.0+ | `composer --version` |

### Recommended Extensions

For optimal performance, ensure these PHP extensions are installed:

- `ext-json` (required)
- `ext-mbstring` (required)
- `ext-openssl` (required)
- `ext-pdo` (required)
- `ext-gd` or `ext-imagick` (for image processing)
- `ext-zip` (for import/export features)

## Installation Methods

CamboAdmin offers three installation approaches:

1. **Interactive Installation** (Recommended) - Guided wizard with prompts
2. **Full Installation** - Installs all modules and features
3. **Minimal Installation** - Only core features, add modules as needed

## Step 1: Install via Composer

Add CamboAdmin to your Laravel project:

```bash
composer require cambosoftware/cambo-admin
```

This will download the package and register the service provider automatically.

### Optional Dependencies

For additional features, you may want to install:

```bash
# For PDF exports
composer require barryvdh/laravel-dompdf

# For Excel exports
composer require maatwebsite/excel

# For image optimization
composer require spatie/laravel-image-optimizer
```

## Step 2: Run the Installer

### Interactive Installation (Recommended)

The interactive installer guides you through the setup process:

```bash
php artisan cambo:install
```

The wizard will prompt you to:

1. Choose which modules to install
2. Configure your admin route prefix
3. Set up the initial administrator account
4. Choose whether to run migrations
5. Choose whether to seed demo data

### Full Installation

Install everything at once without prompts:

```bash
php artisan cambo:install --full
```

This installs all modules with default settings:
- All 12 modules enabled
- Admin route prefix: `/admin`
- Runs migrations automatically
- Creates tables but no demo data

### Minimal Installation

Install only the core features:

```bash
php artisan cambo:install --minimal
```

This installs:
- Authentication module only
- Basic user management
- No additional modules (add them later)

### Installation Options Reference

```bash
# Full installation with all modules
php artisan cambo:install --full

# Minimal installation (auth only)
php artisan cambo:install --minimal

# Install specific modules only
php artisan cambo:install --only=auth,users,roles

# Skip migration execution
php artisan cambo:install --no-migrate

# Skip seeder execution
php artisan cambo:install --no-seed

# Skip database configuration check
php artisan cambo:install --no-db-setup

# Force overwrite existing files
php artisan cambo:install --force

# Non-interactive mode with defaults
php artisan cambo:install --no-interaction

# Combine options
php artisan cambo:install --full --no-seed --force
```

### Database Configuration

The installer automatically checks your database connection before running migrations. If the connection fails, you'll be prompted to configure your database interactively:

1. **Choose database type**: MySQL, PostgreSQL, or SQLite
2. **Enter credentials**: Host, port, database name, username, password
3. **Auto-create database**: If the database doesn't exist, the installer can create it for you

The installer updates your `.env` file with the provided credentials.

::: tip
Use `--no-db-setup` if your database is already configured and you want to skip the connection check.
:::

## Step 3: Install Frontend Dependencies

After the Artisan installer completes, install the JavaScript dependencies:

```bash
# Using npm
npm install

# Or using pnpm (faster)
pnpm install

# Or using yarn
yarn install
```

## Step 4: Build Frontend Assets

### For Production

Build optimized assets for production:

```bash
npm run build
```

This creates minified, optimized bundles in your `public/build` directory.

### For Development

Start the development server with hot module replacement:

```bash
npm run dev
```

This enables:
- Hot module replacement (HMR) for instant updates
- Source maps for debugging
- Faster rebuilds during development

## Step 5: Configure Your User Model

Add the `HasRoles` trait to your User model to enable role and permission functionality:

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use CamboSoftware\CamboAdmin\Models\Traits\LogsActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles;       // Enables roles and permissions
    use LogsActivity;   // Optional: enables activity logging
    use Notifiable;     // For notifications

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

## Step 6: Verify Installation

### Check Routes

Verify CamboAdmin routes are registered:

```bash
php artisan route:list --name=cambo
```

You should see routes like:
- `GET /admin` - Dashboard
- `GET /admin/login` - Login page
- `GET /admin/users` - User management
- etc.

### Check Commands

Verify CLI commands are available:

```bash
php artisan list cambo
```

Available commands:
- `cambo:install` - Install CamboAdmin
- `cambo:crud` - Generate CRUD operations
- `cambo:page` - Generate a new page
- `cambo:component` - Generate a Vue component
- `cambo:add` - Add a module

### Access the Admin Panel

1. Start the Laravel development server:

```bash
php artisan serve
```

2. In a separate terminal, start Vite:

```bash
npm run dev
```

3. Visit `http://localhost:8000/admin` in your browser

If you ran the seeder, you can log in with:
- **Email:** admin@example.com
- **Password:** password

## Manual Installation

If you prefer manual control over the installation process:

### 1. Publish Configuration

```bash
php artisan vendor:publish --tag=cambo-admin-config
```

This creates `config/cambo-admin.php`.

### 2. Publish Migrations

```bash
php artisan vendor:publish --tag=cambo-admin-migrations
```

Migrations are copied to `database/migrations/`.

### 3. Publish Assets

```bash
php artisan vendor:publish --tag=cambo-admin-assets
```

Vue components and CSS are copied to `resources/`.

### 4. Publish Views (Optional)

```bash
php artisan vendor:publish --tag=cambo-admin-views
```

Blade templates are copied to `resources/views/vendor/cambo-admin/`.

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Run Seeders (Optional)

```bash
php artisan db:seed --class=CamboAdminSeeder
```

This creates:
- Default roles (Super Admin, Admin, User)
- Default permissions
- An admin user (admin@example.com / password)

## Inertia.js Configuration

Ensure your `resources/js/app.js` is configured for Inertia:

```javascript
import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'CamboAdmin';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#6366f1',
    },
});
```

## Vite Configuration

Ensure your `vite.config.js` includes the Laravel plugin:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

## Running in Development

For the best development experience, run both servers:

### Option 1: Separate Terminals

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### Option 2: Combined Script

Add to your `composer.json`:

```json
{
    "scripts": {
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently \"php artisan serve\" \"npm run dev\""
        ]
    }
}
```

Then run:

```bash
composer dev
```

### Option 3: Using Laravel Herd or Valet

If using Laravel Herd or Valet, you only need to run Vite:

```bash
npm run dev
```

Visit your site at `http://your-project.test/admin`.

## Troubleshooting

### "Class not found" errors

Run Composer autoload:

```bash
composer dump-autoload
```

### Assets not loading

Clear caches and rebuild:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
npm run build
```

### Permission denied errors

Ensure proper directory permissions:

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database migration errors

Check your database connection in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Vite manifest not found

Build the assets:

```bash
npm run build
```

For development, ensure Vite is running:

```bash
npm run dev
```

## Next Steps

Now that CamboAdmin is installed:

1. **[Configure CamboAdmin](/guide/configuration)** - Customize settings and appearance
2. **[Quick Start Tutorial](/guide/quick-start)** - Build your first CRUD
3. **[Explore Components](/components/)** - Browse the component library
4. **[Learn the CLI](/guide/cli/crud)** - Master the code generators
