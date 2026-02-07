# cambo:add Command

The `cambo:add` command allows you to add CamboAdmin modules after the initial installation. This is useful when you want to start with a minimal setup and add features as needed.

## Basic Usage

```bash
php artisan cambo:add notifications
```

This adds the notifications module to your CamboAdmin installation.

## Command Signature

```bash
php artisan cambo:add
    {module : The module to add (auth, roles, notifications, etc.)}
    {--force : Overwrite existing files}
```

## Options

### `module` (Required)

The name of the module to add. Must be one of the available modules listed below.

```bash
php artisan cambo:add auth
php artisan cambo:add roles
php artisan cambo:add notifications
```

### `--force`

Overwrite existing files when publishing module assets.

```bash
php artisan cambo:add dashboard --force
```

## Available Modules

| Module | Description | Dependencies |
|--------|-------------|--------------|
| `auth` | Authentication (login, register, 2FA) | None |
| `users` | User management | `auth` |
| `roles` | Role management | `auth` |
| `notifications` | Notification center | `auth` |
| `activity-log` | Activity logging | `auth` |
| `dashboard` | Customizable dashboard | `auth` |
| `media` | File manager | `auth` |
| `settings` | Dynamic settings | `auth` |
| `import-export` | Import/Export functionality | None |
| `i18n` | Multi-language support | None |
| `themes` | Theme customization | None |

## Module Details

### auth

Authentication module with login, registration, and two-factor authentication.

```bash
php artisan cambo:add auth
```

**Includes:**
- Login/Logout pages
- Registration pages
- Password reset flow
- Two-factor authentication
- User model migrations

**Files Published:**
- Migration: `add_two_factor_columns_to_users_table`
- Pages: `Auth/Login.vue`, `Auth/Register.vue`, etc.
- Controllers: Authentication controllers

### users

User management module for CRUD operations on users.

```bash
php artisan cambo:add users
```

**Requires:** `auth`

**Includes:**
- User list with search and pagination
- Create/Edit user forms
- User profile pages
- Bulk actions

**Files Published:**
- Pages: `Users/Index.vue`, `Users/Create.vue`, etc.

### roles

Role and permission management module.

```bash
php artisan cambo:add roles
```

**Requires:** `auth`

**Includes:**
- Role CRUD
- Permission assignment
- User-role assignment
- Role-based access control

**Files Published:**
- Migration: `create_roles_and_permissions_tables`
- Pages: `Roles/Index.vue`, `Roles/Create.vue`, etc.
- Seeder: `RolesAndPermissionsSeeder`

### notifications

Notification center for in-app notifications.

```bash
php artisan cambo:add notifications
```

**Requires:** `auth`

**Includes:**
- Notification bell with counter
- Notification list/dropdown
- Mark as read/unread
- Notification preferences

**Files Published:**
- Migration: `create_notifications_table`
- Pages: `Notifications/Index.vue`

### activity-log

Activity logging module for tracking user actions.

```bash
php artisan cambo:add activity-log
```

**Requires:** `auth`

**Includes:**
- Activity log viewer
- Filter by user/action/model
- Export activity logs
- Automatic logging trait

**Files Published:**
- Migration: `create_activity_logs_table`
- Pages: `ActivityLog/Index.vue`

### dashboard

Customizable dashboard with widgets.

```bash
php artisan cambo:add dashboard
```

**Requires:** `auth`

**Includes:**
- Drag-and-drop widget layout
- Pre-built widgets (stats, charts, lists)
- Widget configuration
- Per-user dashboard preferences

**Files Published:**
- Migration: `create_dashboard_widgets_table`
- Seeder: `WidgetTypesSeeder`

### media

File manager for uploading and managing files.

```bash
php artisan cambo:add media
```

**Requires:** `auth`

**Includes:**
- File upload with drag-and-drop
- Image preview and cropping
- Folder organization
- File search and filtering

**Files Published:**
- Migration: `create_media_files_table`
- Pages: `Media/Index.vue`

### settings

Dynamic settings management.

```bash
php artisan cambo:add settings
```

**Requires:** `auth`

**Includes:**
- Key-value settings storage
- Settings groups
- Settings UI
- Settings API

**Files Published:**
- Migration: `create_settings_table`
- Pages: `Settings/Index.vue`
- Seeder: `SettingsSeeder`

### import-export

Data import and export functionality.

```bash
php artisan cambo:add import-export
```

**Includes:**
- CSV/Excel import
- Data mapping interface
- Export templates
- Scheduled exports

**Files Published:**
- Pages: `ImportExport/Index.vue`

### i18n

Multi-language support for the admin panel.

```bash
php artisan cambo:add i18n
```

**Includes:**
- Translation management UI
- Language switcher
- Translation files
- RTL support

**Files Published:**
- Pages: `Translations/Index.vue`
- Language files in `lang/` directory

### themes

Theme customization for the admin panel.

```bash
php artisan cambo:add themes
```

**Includes:**
- Theme switcher
- Custom color schemes
- Dark mode toggle
- Theme preferences

**Files Published:**
- Pages: `Theme/Index.vue`

## Examples

### Add Single Module

```bash
php artisan cambo:add notifications
```

Output:
```
Adding module: notifications (Notification center)
✓ Module enabled in config
✓ Migrations published
✓ Pages published
Run migrations now? (yes/no) [yes]:
✓ Module 'notifications' added successfully!
```

### Add Module with Dependencies

When adding a module that has dependencies, they are automatically installed:

```bash
php artisan cambo:add roles
```

Output:
```
Adding module: roles (Role management)
Module 'roles' requires 'auth'. Installing it first...
Adding module: auth (Authentication (login, register, 2FA))
✓ Module enabled in config
✓ Migrations published
✓ Pages published
✓ Module 'auth' added successfully!
✓ Module enabled in config
✓ Migrations published
✓ Pages published
✓ Seeders published
Run migrations now? (yes/no) [yes]:
Run seeders now? (yes/no) [yes]:
✓ Module 'roles' added successfully!
```

### Force Overwrite

```bash
php artisan cambo:add dashboard --force
```

### Skip Migrations and Seeders

When prompted, answer "no" to skip:

```bash
php artisan cambo:add settings
# ...
Run migrations now? (yes/no) [yes]: no
Run seeders now? (yes/no) [yes]: no
```

## What Happens During Module Addition

### 1. Dependency Check

The command checks if required modules are installed and installs them if needed.

### 2. Configuration Update

The module is enabled in `config/cambo-admin.php`:

```php
'modules' => [
    // ...
    'notifications' => true,  // Changed from false to true
    // ...
],
```

### 3. Migration Publishing

Module-specific migrations are published to `database/migrations/`.

### 4. Page Publishing

Vue pages for the module are published to `resources/js/Pages/`.

### 5. Seeder Publishing

If the module has seeders, they are published to `database/seeders/`.

### 6. Migration Execution

If confirmed, migrations are run with `php artisan migrate`.

### 7. Seeder Execution

If confirmed, seeders are run for initial data.

## Removing Modules

To disable a module (without removing files):

1. Set the module to `false` in `config/cambo-admin.php`:

```php
'modules' => [
    'notifications' => false,
],
```

2. Clear the configuration cache:

```bash
php artisan config:clear
```

To completely remove a module:

1. Disable in config (as above)
2. Remove migration files (or roll back)
3. Remove published Vue pages
4. Clear any related database tables

```bash
# Roll back specific migration
php artisan migrate:rollback --path=database/migrations/xxxx_xx_xx_create_notifications_table.php

# Or drop the table
php artisan tinker
>>> Schema::dropIfExists('notifications')
```

## Verifying Module Status

Check which modules are enabled:

```bash
php artisan tinker
>>> config('cambo-admin.modules')
```

Or check the configuration file:

```bash
cat config/cambo-admin.php | grep -A 20 "'modules'"
```

## Troubleshooting

### Module Not Found

```
Unknown module: unknown-module
Available modules: auth, users, roles, notifications, activity-log, dashboard, media, settings, import-export, i18n, themes
```

Check the spelling and use only available module names.

### Config File Not Found

```
Config file not found. Publishing...
```

The command will automatically publish the config file if missing.

### Dependency Issues

If a module fails due to missing dependencies:

```bash
# Install the dependency first
php artisan cambo:add auth

# Then install the dependent module
php artisan cambo:add users
```

### Migration Conflicts

If migrations already exist:

```bash
# Use --force to overwrite
php artisan cambo:add roles --force
```

Or remove existing migrations first:

```bash
rm database/migrations/*_create_roles_and_permissions_tables.php
php artisan cambo:add roles
```

## See Also

- [cambo:install](./install.md) - Initial installation
- [Configuration](/guide/configuration.md) - Configuration options
- [Modules](/guide/features/) - Module documentation
