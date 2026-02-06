# Introduction

CamboAdmin is a complete Laravel package for building modern and professional admin panels. It combines the power of Laravel 12 with the reactivity of Vue 3 and Inertia.js.

## What is CamboAdmin?

CamboAdmin is an all-in-one solution that provides:

- **A complete UI framework** with over 134 Vue 3 components
- **A solid architecture** based on Laravel best practices
- **Essential pre-built features** (auth, roles, notifications...)
- **CLI tools** to generate code quickly

## Requirements

Before installing CamboAdmin, make sure your environment meets the following requirements:

- **PHP** >= 8.2
- **Laravel** >= 12.0
- **Node.js** >= 18.0
- **Composer** >= 2.0

## Tech Stack

CamboAdmin uses the following technologies:

| Technology | Version | Description |
|------------|---------|-------------|
| Laravel | 12.x | PHP Framework |
| Vue.js | 3.x | JavaScript Framework |
| Inertia.js | 2.x | SPA Adapter |
| Tailwind CSS | 3.x | CSS Framework |
| Heroicons | 2.x | SVG Icons |

## Package Structure

```
cambo-admin/
├── config/           # Configuration files
├── database/
│   ├── migrations/   # Database migrations
│   └── seeders/      # Seeders
├── resources/
│   ├── js/          # Vue components and pages
│   └── views/       # Blade templates
├── routes/          # Route definitions
├── src/
│   ├── Console/     # Artisan commands
│   ├── Http/        # Controllers and Middleware
│   ├── Models/      # Eloquent models
│   └── Services/    # Business services
└── stubs/           # Templates for generators
```

## Available Modules

CamboAdmin is organized into modules that you can enable or disable according to your needs:

| Module | Description | Enabled by Default |
|--------|-------------|-------------------|
| `auth` | Authentication (login, register, 2FA) | Yes |
| `users` | User management | Yes |
| `roles` | Role management | Yes |
| `permissions` | Permission management | Yes |
| `notifications` | Notification system | Yes |
| `activity-log` | Activity log | Yes |
| `dashboard` | Dashboard | Yes |
| `media` | File manager | Yes |
| `settings` | Dynamic settings | Yes |
| `import-export` | Data import/export | Yes |
| `i18n` | Internationalization | Yes |
| `themes` | Theme management | Yes |

## Support

If you encounter problems or have questions:

- **Documentation**: You're here!
- **GitHub Issues**: [github.com/cambosoftware/cambo-admin/issues](https://github.com/cambosoftware/cambo-admin/issues)
- **Discussions**: [github.com/cambosoftware/cambo-admin/discussions](https://github.com/cambosoftware/cambo-admin/discussions)

## Contributing

Contributions are welcome! Check out our [contribution guide](https://github.com/cambosoftware/cambo-admin/blob/main/CONTRIBUTING.md) for more details.

## License

CamboAdmin is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
