# Installation

## Install via Composer

```bash
composer require cambosoftware/cambo-admin
```

## Interactive Installation

Run the installation wizard:

```bash
php artisan cambo:install
```

This command will:
1. Publish configuration files
2. Publish assets (Vue, CSS, images)
3. Publish migrations
4. Run migrations
5. Create an administrator user
6. Configure basic settings

### Installation Options

```bash
# Full installation (all modules)
php artisan cambo:install --full

# Install specific modules only
php artisan cambo:install --only=auth,users,roles

# Without running migrations
php artisan cambo:install --no-migrate

# Without running seeders
php artisan cambo:install --no-seed

# Force overwrite existing files
php artisan cambo:install --force
```

## Manual Installation

If you prefer manual installation:

### 1. Publish Configuration

```bash
php artisan vendor:publish --tag=cambo-admin-config
```

### 2. Publish Migrations

```bash
php artisan vendor:publish --tag=cambo-admin-migrations
```

### 3. Publish Assets

```bash
php artisan vendor:publish --tag=cambo-admin-assets
```

### 4. Publish Views

```bash
php artisan vendor:publish --tag=cambo-admin-views
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Run Seeders

```bash
php artisan db:seed --class=CamboAdminSeeder
```

## User Model Configuration

Add the `HasRoles` trait to your User model:

```php
<?php

namespace App\Models;

use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

## Inertia.js Configuration

Make sure your `app.js` file is properly configured:

```javascript
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

createInertiaApp({
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
```

## Verify Installation

After installation, verify everything works:

```bash
# Check available routes
php artisan route:list --name=cambo

# Check available commands
php artisan list cambo
```

You should see these commands:
- `cambo:install`
- `cambo:crud`
- `cambo:page`
- `cambo:component`
- `cambo:add`

## Start Development Server

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite (assets)
npm run dev
```

Or use the combined script:

```bash
composer dev
```

Visit `http://localhost:8000/admin` to access the admin panel.
