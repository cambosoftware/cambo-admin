# CamboAdmin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cambosoftware/cambo-admin.svg?style=flat-square)](https://packagist.org/packages/cambosoftware/cambo-admin)
[![Total Downloads](https://img.shields.io/packagist/dt/cambosoftware/cambo-admin.svg?style=flat-square)](https://packagist.org/packages/cambosoftware/cambo-admin)
[![Tests](https://github.com/cambosoftware/cambo-admin/actions/workflows/tests.yml/badge.svg)](https://github.com/cambosoftware/cambo-admin/actions)
[![License](https://img.shields.io/packagist/l/cambosoftware/cambo-admin.svg?style=flat-square)](https://packagist.org/packages/cambosoftware/cambo-admin)
[![Documentation](https://img.shields.io/badge/docs-cambo--admin.cambosoftware.com-blue.svg?style=flat-square)](https://cambo-admin.cambosoftware.com)

A complete Laravel backoffice package with 150+ Vue.js components, authentication, roles & permissions, and more. Build beautiful admin panels in minutes.

## Documentation

**Full documentation is available at [cambo-admin.cambosoftware.com](https://cambo-admin.cambosoftware.com)**

- [Getting Started](https://cambo-admin.cambosoftware.com/guide/)
- [Installation Guide](https://cambo-admin.cambosoftware.com/guide/installation)
- [Configuration](https://cambo-admin.cambosoftware.com/guide/configuration)
- [Components Reference](https://cambo-admin.cambosoftware.com/components/)
- [API Reference](https://cambo-admin.cambosoftware.com/api/)

## Features

- **150+ Vue.js Components** - Buttons, Forms, Tables, Modals, Charts, and more
- **Authentication** - Login, Register, 2FA, Sessions, Password Reset
- **Roles & Permissions** - Granular permission system with middleware
- **Notifications** - Real-time notification center
- **Activity Log** - Automatic activity tracking
- **Dashboard Builder** - Drag & drop customizable widgets
- **File Manager** - Upload, organize, and manage files (local/S3)
- **Settings Manager** - Dynamic settings by groups
- **Import/Export** - CSV, Excel, PDF support
- **Multi-language (i18n)** - RTL support, translation management
- **Themes** - Customizable color themes
- **CRUD Generator** - Generate complete CRUD with one command

## Requirements

- PHP 8.2+
- Laravel 11+ or 12+
- Node.js 18+
- Inertia.js 2.0+

## Installation

```bash
composer require cambosoftware/cambo-admin
```

### Full Installation (Recommended)

```bash
php artisan cambo:install --full
```

This will:
- Publish all configuration and assets
- Run migrations
- Seed default data (roles, permissions, settings, widgets)
- Create an admin user

### Interactive Installation

```bash
php artisan cambo:install
```

Choose which modules to install interactively.

### Minimal Installation (UI only)

```bash
php artisan cambo:install --only=ui
```

Install only the Vue.js components without backend features.

## After Installation

```bash
npm install
npm run build
```

Visit your application at `http://localhost/admin`

## Configuration

After installation, customize the package via `config/cambo-admin.php`:

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
        // ...
    ],

    'appearance' => [
        'name' => 'My Admin',
        'primary_color' => '#6366f1',
        'dark_mode' => 'auto',
    ],

    'routes' => [
        'prefix' => 'admin',
        'middleware' => ['web', 'auth', 'verified'],
    ],
];
```

## CLI Commands

### Generate CRUD

```bash
php artisan cambo:crud Product --fields="name:string,price:decimal,active:boolean"
```

Generates:
- Migration
- Model with fillable
- Controller with all CRUD methods
- 4 Vue pages (Index, Create, Edit, Show)
- Routes

### Generate Vue Page

```bash
php artisan cambo:page Reports/Analytics --title="Analytics" --with-card --with-table
```

### Generate Vue Component

```bash
php artisan cambo:component StatsCard --category=Widgets --with-props --with-emits
```

### Add Module After Installation

```bash
php artisan cambo:add notifications
```

## Components

### Layout (8)
AdminLayout, Sidebar, SidebarItem, SidebarDivider, Navbar, Breadcrumb, PageHeader, Container

### UI (12)
Button, ButtonGroup, IconButton, Badge, Avatar, AvatarGroup, Icon, Spinner, Skeleton, Tooltip, Divider, AppLink

### Overlays (8)
Modal, ConfirmModal, Drawer, Dropdown, DropdownItem, DropdownDivider, Popover, ContextMenu

### Feedback (6)
Alert, Toast, ToastContainer, ProgressBar, EmptyState, ErrorState

### Containers (8)
Card, CardGrid, Accordion, AccordionItem, Tabs, Tab, Collapse, Panel

### Forms - Basic (14)
Form, FormGroup, Input, Textarea, Select, SelectSearch, SelectMultiple, Checkbox, CheckboxGroup, Radio, RadioGroup, RadioCards, Switch, Toggle

### Forms - Advanced (20)
DatePicker, DateRangePicker, TimePicker, DateTimePicker, ColorPicker, FilePicker, ImagePicker, FileDropzone, RichTextEditor, MarkdownEditor, CodeEditor, TagInput, SliderInput, RangeInput, RatingInput, PasswordInput, SearchInput, PhoneInput, CurrencyInput, NumberInput

### Data Display (16 + 10 + 12)
Table, TableHead, TableBody, TableRow, TableCell, SortableHeader, Pagination, List, ListItem, DescriptionList, Tree, Timeline, DataTable, and 22 sub-components/formatters

### Charts (9)
Chart (wrapper), LineChart, AreaChart, BarChart, DonutChart, PieChart, StatCard, StatGrid, MiniChart

### Navigation (4)
NavLink, NavGroup, StepWizard, BackButton

### Utilities (7)
CopyButton, ClickToCopy, ExternalLink, Highlight, RelativeTime, CountUp, Kbd

## Usage Example

### Controller

```php
use CamboSoftware\CamboAdmin\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index()
    {
        return inertia('Users/Index', [
            'users' => QueryBuilder::for(User::class)
                ->columns(['id', 'name', 'email', 'created_at'])
                ->searchable(['name', 'email'])
                ->sortable(['name', 'created_at'])
                ->exportable(['csv', 'excel'])
                ->paginate(25)
        ]);
    }
}
```

### Vue Page

```vue
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import DataTable from '@/Components/Data/DataTable.vue'

defineProps({ users: Object })
</script>

<template>
    <AdminLayout title="Users">
        <DataTable :resource="users" />
    </AdminLayout>
</template>
```

## Testing

```bash
cd packages/cambosoftware/cambo-admin
composer install
./vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email contact@cambosoftware.com instead of using the issue tracker.

## Credits

- [CamboSoftware](https://github.com/cambosoftware)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
