# Introduction

Welcome to CamboAdmin, the complete Laravel package for building modern, professional admin panels. CamboAdmin combines the power of Laravel with the reactivity of Vue 3 and the seamless SPA experience of Inertia.js to help you create beautiful admin interfaces in minutes, not days.

## What is CamboAdmin?

CamboAdmin is an all-in-one admin panel solution that provides everything you need to build professional back-office applications:

- **A Complete UI Framework** - Over 150 beautifully designed Vue 3 components built with Tailwind CSS
- **A Solid Architecture** - Built on Laravel best practices with clean, maintainable code
- **Essential Pre-built Features** - Authentication, roles & permissions, notifications, file management, and more
- **Powerful CLI Tools** - Generate CRUD operations, pages, and components with simple commands
- **Developer Experience** - TypeScript support, hot module replacement, and comprehensive documentation

## Who is CamboAdmin For?

CamboAdmin is designed for:

- **Laravel Developers** who want to build admin panels quickly without reinventing the wheel
- **Freelancers** who need to deliver professional admin interfaces to clients efficiently
- **Startups** that need a solid foundation for their admin dashboard
- **Enterprise Teams** looking for a maintainable, well-documented solution
- **Anyone** who values clean code and modern development practices

## Requirements

Before installing CamboAdmin, ensure your environment meets these requirements:

| Requirement | Minimum Version | Recommended |
|-------------|-----------------|-------------|
| **PHP** | 8.2+ | 8.3+ |
| **Laravel** | 11.0+ | 12.0+ |
| **Node.js** | 18.0+ | 20.0+ (LTS) |
| **Composer** | 2.0+ | Latest |
| **npm** or **pnpm** | npm 9+ / pnpm 8+ | Latest |

### Database Support

CamboAdmin works with all databases supported by Laravel:

- MySQL 8.0+
- PostgreSQL 14+
- SQLite 3.35+
- SQL Server 2019+

### Browser Support

The frontend is optimized for modern browsers:

- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)

## Tech Stack

CamboAdmin leverages the best modern web technologies:

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 11.x / 12.x | Backend PHP framework with elegant syntax |
| **Vue.js** | 3.x | Progressive JavaScript framework with Composition API |
| **Inertia.js** | 2.x | SPA adapter - build SPAs without building an API |
| **Tailwind CSS** | 3.x | Utility-first CSS framework for rapid UI development |
| **Heroicons** | 2.x | Beautiful hand-crafted SVG icons |
| **Vite** | 5.x | Next-generation frontend build tool |
| **TypeScript** | 5.x | Optional type safety for JavaScript |

## Package Structure

Understanding the package structure helps you customize and extend CamboAdmin:

```
cambo-admin/
├── config/
│   └── cambo-admin.php      # Main configuration file
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── js/
│   │   ├── Components/     # 150+ Vue components
│   │   ├── Composables/    # Reusable composition functions
│   │   ├── Layouts/        # Page layouts
│   │   ├── Pages/          # Inertia pages
│   │   └── types/          # TypeScript type definitions
│   ├── css/                # Stylesheets
│   └── views/              # Blade templates
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes (if applicable)
├── src/
│   ├── Console/
│   │   └── Commands/       # Artisan commands
│   ├── Http/
│   │   ├── Controllers/    # HTTP controllers
│   │   ├── Middleware/     # Request middleware
│   │   └── Requests/       # Form request validation
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic services
│   └── Traits/             # Reusable traits
├── stubs/                  # Templates for code generation
└── tests/                  # Package tests
```

## Available Modules

CamboAdmin is organized into modular features that you can enable or disable based on your needs:

| Module | Description | Dependencies | Default |
|--------|-------------|--------------|---------|
| `auth` | Authentication (login, register, 2FA, sessions) | None | Enabled |
| `users` | User management CRUD | `auth` | Enabled |
| `roles` | Role management | `auth` | Enabled |
| `permissions` | Granular permission system | `roles` | Enabled |
| `notifications` | Database and real-time notifications | `auth` | Enabled |
| `activity-log` | User action tracking and audit trail | `auth` | Enabled |
| `dashboard` | Customizable dashboard with widgets | `auth` | Enabled |
| `media` | File manager for uploads and media | `auth` | Enabled |
| `settings` | Dynamic application settings | None | Enabled |
| `import-export` | Data import/export (CSV, Excel, PDF) | None | Enabled |
| `i18n` | Multi-language support with RTL | None | Enabled |
| `themes` | Theme customization and dark mode | None | Enabled |

### Module Dependencies

Some modules depend on others:

```
auth
├── users
├── roles
│   └── permissions
├── notifications
├── activity-log
├── dashboard
└── media
```

Enabling a module automatically enables its dependencies.

## Core Concepts

### Inertia.js Approach

CamboAdmin uses Inertia.js as a bridge between Laravel and Vue. This means:

- **No API Required** - Your controllers return Inertia responses, not JSON
- **Server-Side Routing** - Use Laravel's router, not Vue Router
- **Shared Data** - Easily share data between the server and client
- **Form Handling** - Inertia handles form submissions with validation errors

```php
// Controller returns an Inertia response
public function index()
{
    return Inertia::render('Users/Index', [
        'users' => User::paginate(15),
    ]);
}
```

### Component Architecture

All UI components follow consistent patterns:

- **Props-Based Configuration** - Components are configured via props
- **Slots for Customization** - Extend components using Vue slots
- **Event Emission** - Components communicate via events
- **Composables for Logic** - Reusable logic extracted into composables

### Permission-Based UI

The UI automatically respects permissions:

```vue
<template>
  <Button v-if="can('users.create')" @click="createUser">
    Create User
  </Button>
</template>
```

## Getting Help

If you encounter issues or have questions:

### Documentation

You're reading it! Use the sidebar to navigate to specific topics.

### GitHub Issues

For bug reports and feature requests:
[github.com/cambosoftware/cambo-admin/issues](https://github.com/cambosoftware/cambo-admin/issues)

### GitHub Discussions

For questions, ideas, and community help:
[github.com/cambosoftware/cambo-admin/discussions](https://github.com/cambosoftware/cambo-admin/discussions)

### Discord Community

Join our Discord for real-time help:
[discord.gg/cambosoftware](https://discord.gg/cambosoftware)

## Contributing

We welcome contributions! Whether it's:

- Reporting bugs
- Suggesting features
- Improving documentation
- Submitting pull requests

Check our [Contributing Guide](https://github.com/cambosoftware/cambo-admin/blob/main/CONTRIBUTING.md) for details.

## License

CamboAdmin is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT). You are free to use, modify, and distribute it in personal and commercial projects.

---

Ready to get started? Continue to the [Installation Guide](/guide/installation) to set up CamboAdmin in your Laravel project.
