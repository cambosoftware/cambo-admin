# cambo:install Command

The `cambo:install` command is the primary installation command for CamboAdmin. It sets up the package with an interactive wizard or allows automated installation with command-line options.

## Basic Usage

```bash
php artisan cambo:install
```

This launches an interactive wizard that guides you through the installation process.

## Command Signature

```bash
php artisan cambo:install
    {--full : Install all modules without prompts}
    {--only=* : Install only specific modules}
    {--no-migrate : Skip database migrations}
    {--no-seed : Skip database seeding}
    {--force : Overwrite existing files}
```

## Options

### `--full`

Installs all available modules without any interactive prompts. This is the recommended option for production environments or automated deployments.

```bash
php artisan cambo:install --full
```

### `--only`

Install only specific modules. You can specify multiple modules by repeating the option or using comma-separated values.

```bash
# Install only auth and users modules
php artisan cambo:install --only=auth --only=users

# Or using comma separation
php artisan cambo:install --only=auth,users,roles
```

### `--no-migrate`

Skip running database migrations. Useful when you want to review migrations before running them.

```bash
php artisan cambo:install --no-migrate

# Then run migrations manually
php artisan migrate
```

### `--no-seed`

Skip running database seeders. Use this if you want to customize seeder data before running.

```bash
php artisan cambo:install --no-seed

# Then run seeders manually
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=WidgetTypesSeeder
```

### `--force`

Force overwrite existing files. Use with caution as this will replace any customizations you've made.

```bash
php artisan cambo:install --force
```

## Available Modules

The installer can configure the following modules:

| Module | Description |
|--------|-------------|
| `auth` | Authentication (login, register, 2FA) |
| `users` | User management |
| `roles` | Role management |
| `permissions` | Granular permissions |
| `notifications` | Notification center |
| `activity-log` | Activity logging |
| `dashboard` | Customizable dashboard |
| `media` | File manager |
| `settings` | Dynamic settings |
| `import-export` | Import/Export functionality |
| `i18n` | Multi-language support |
| `themes` | Theme customization |

## Installation Types

When running interactively (without options), you'll be prompted to choose an installation type:

### Complete Installation (Recommended)

Installs all modules for a fully-featured admin panel.

### Custom Installation

Allows you to select specific modules to install. You'll be prompted for each module individually.

### Minimal Installation

Installs only UI components without any modules. Ideal for projects that only need the component library.

## What Gets Installed

The installation process performs the following steps:

### 1. Configuration

Publishes the `config/cambo-admin.php` configuration file and enables selected modules.

```bash
php artisan vendor:publish --tag=cambo-admin-config
```

### 2. Assets

Publishes Vue components, composables, plugins, and views:

```bash
php artisan vendor:publish --tag=cambo-admin-components
php artisan vendor:publish --tag=cambo-admin-composables
php artisan vendor:publish --tag=cambo-admin-plugins
php artisan vendor:publish --tag=cambo-admin-views
```

### 3. Pages

If modules are selected, publishes the Vue pages:

```bash
php artisan vendor:publish --tag=cambo-admin-pages
```

### 4. Language Files

If the `i18n` module is enabled:

```bash
php artisan vendor:publish --tag=cambo-admin-lang
```

### 5. Migrations

Publishes database migration files:

```bash
php artisan vendor:publish --tag=cambo-admin-migrations
```

### 6. Seeders

Publishes seeder files:

```bash
php artisan vendor:publish --tag=cambo-admin-seeders
```

### 7. File Updates

The installer automatically updates:

- **User Model**: Adds `HasRoles` and `LogsActivity` traits if those modules are enabled
- **vite.config.js**: Adds the `@` alias for imports
- **tailwind.config.js**: Adds content paths for Vue files
- **app.js**: Configures Inertia.js with proper page resolution

### 8. Admin User Creation

If the `auth` module is enabled, you'll be prompted to create an admin user:

```
Would you like to create an admin user? (yes/no) [yes]:
Admin email [admin@example.com]:
Admin password (min 8 characters):
Admin name [Admin]:
```

## Examples

### Quick Full Installation

```bash
php artisan cambo:install --full
```

### Minimal API Project

```bash
php artisan cambo:install --only=auth,users,roles --no-seed
```

### Review Before Migrating

```bash
php artisan cambo:install --full --no-migrate --no-seed

# Review migrations
ls database/migrations/

# Run when ready
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### CI/CD Pipeline Installation

```bash
php artisan cambo:install --full --force --no-migrate
php artisan migrate --force
```

## Post-Installation

After installation, complete these steps:

### 1. Install NPM Dependencies

```bash
npm install
```

### 2. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 3. Start the Server

```bash
php artisan serve
```

### 4. Access the Admin Panel

Visit `http://localhost:8000/admin` to access your new admin panel.

## Troubleshooting

### Configuration Not Loading

Clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

### Assets Not Appearing

Rebuild the assets:

```bash
npm run build
```

### Permission Issues

Ensure proper permissions on storage and bootstrap/cache:

```bash
chmod -R 775 storage bootstrap/cache
```

## See Also

- [cambo:crud](./crud.md) - Generate CRUD modules
- [cambo:page](./page.md) - Generate pages
- [cambo:component](./component.md) - Generate components
- [cambo:add](./add-module.md) - Add modules after installation
