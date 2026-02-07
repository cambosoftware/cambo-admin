---
home: true
heroImage: /images/hero.svg
heroText: CamboAdmin
tagline: Build beautiful, production-ready admin panels in minutes with Laravel and Vue.js
actions:
  - text: Get Started
    link: /guide/
    type: primary
  - text: View Components
    link: /components/
    type: secondary
  - text: GitHub
    link: https://github.com/cambosoftware/cambo-admin
    type: secondary
features:
  - title: 150+ Vue Components
    details: A comprehensive library of beautiful, accessible, and fully customizable UI components built with Vue 3 Composition API and Tailwind CSS.
  - title: Complete Authentication
    details: Secure login, registration, password reset, email verification, two-factor authentication, and session management out of the box.
  - title: Roles & Permissions
    details: Flexible role-based access control (RBAC) with granular permissions. Easily manage who can access what in your application.
  - title: Dark Mode & Themes
    details: Built-in dark mode with automatic system detection. Customize themes with CSS variables to match your brand.
  - title: Data Import/Export
    details: Export your data to CSV, Excel, or PDF formats. Import data from spreadsheets with validation and error handling.
  - title: Powerful CLI Tools
    details: Generate CRUD operations, pages, and components with simple Artisan commands. Save hours of repetitive coding.
footer: MIT Licensed | Copyright 2024-present CamboSoftware
---

<div class="hero-badges">
  <a href="https://packagist.org/packages/cambosoftware/cambo-admin"><img src="https://img.shields.io/packagist/v/cambosoftware/cambo-admin.svg?style=flat-square" alt="Latest Version on Packagist"></a>
  <a href="https://packagist.org/packages/cambosoftware/cambo-admin"><img src="https://img.shields.io/packagist/dt/cambosoftware/cambo-admin.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://github.com/cambosoftware/cambo-admin/actions"><img src="https://img.shields.io/github/actions/workflow/status/cambosoftware/cambo-admin/tests.yml?style=flat-square" alt="Build Status"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="License"></a>
</div>

## Quick Installation

Get up and running in under 2 minutes:

```bash
# Install the package via Composer
composer require cambosoftware/cambo-admin

# Run the interactive installer
php artisan cambo:install

# Install frontend dependencies and build assets
npm install && npm run build
```

That's it! Visit `http://localhost:8000/admin` to access your new admin panel.

<div class="custom-block tip">
<p class="custom-block-title">Tip</p>
<p>For development, run <code>npm run dev</code> to enable hot module replacement (HMR) for instant UI updates.</p>
</div>

## Why Choose CamboAdmin?

CamboAdmin is designed for Laravel developers who want to build professional admin panels quickly without sacrificing code quality, maintainability, or flexibility.

### Modern Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 11.x / 12.x | Backend PHP framework |
| **Vue.js** | 3.x | Frontend JavaScript framework |
| **Inertia.js** | 2.x | SPA adapter (no API needed) |
| **Tailwind CSS** | 3.x | Utility-first CSS framework |
| **Heroicons** | 2.x | Beautiful hand-crafted SVG icons |

### Built for Productivity

- **150+ Vue Components** - Everything from buttons to complex data tables
- **CLI Code Generators** - Generate CRUDs, pages, and components instantly
- **TypeScript Ready** - Full TypeScript support for type safety
- **Comprehensive Documentation** - Detailed guides and examples

### Enterprise-Ready Features

- **Multi-tenancy Support** - Build SaaS applications with tenant isolation
- **Activity Logging** - Track every user action for compliance and debugging
- **File Manager** - Upload, organize, and manage files with ease
- **Multi-language (i18n)** - Support for multiple languages with RTL layouts
- **Dynamic Settings** - Manage application settings through the UI

## Complete Feature List

### Authentication Module
- Secure login with brute-force protection
- User registration with email verification
- Password reset via email
- Two-factor authentication (2FA) with TOTP
- Session management and device tracking
- Remember me functionality
- Social login integration (Google, GitHub, etc.)

### User Management
- Full CRUD for user administration
- User profile management
- Avatar upload and management
- Account status (active, suspended, banned)
- Bulk actions (delete, export, email)

### Roles & Permissions
- Unlimited roles with inheritance
- Granular permissions system
- Permission groups for organization
- Role-based UI visibility
- Easy-to-use permission checker API

### Dashboard
- Customizable widget-based dashboard
- Real-time statistics and charts
- Drag-and-drop widget arrangement
- Multiple dashboard layouts
- Quick action shortcuts

### Notifications
- Database notifications with persistence
- Real-time push notifications (optional)
- Email notifications
- Notification center with read/unread status
- Bulk mark as read

### Activity Log
- Automatic action logging
- User activity timeline
- Model change tracking
- IP address and user agent logging
- Configurable retention period

### File Manager
- Drag-and-drop file uploads
- Image preview and editing
- Folder organization
- Multiple storage disk support
- File type restrictions
- Maximum upload size configuration

### Data Import/Export
- CSV export with column selection
- Excel export with formatting
- PDF export with templates
- CSV/Excel import with validation
- Import progress tracking
- Error reporting

### Internationalization (i18n)
- Multiple language support
- RTL layout support (Arabic, Hebrew)
- Translation management UI
- Locale switcher component
- Date and number formatting

### Themes & Customization
- Dark mode with system detection
- Custom color schemes
- CSS variable theming
- Sidebar customization
- Logo and branding options

## Component Categories

CamboAdmin includes over 150 components organized into logical categories:

### Layout Components
`AdminLayout` `Sidebar` `Navbar` `Breadcrumb` `PageHeader` `Container` `Footer`

### UI Components
`Button` `ButtonGroup` `IconButton` `Badge` `Avatar` `AvatarGroup` `Icon` `Spinner` `Skeleton` `Tooltip` `Divider` `AppLink`

### Form Components - Basic
`Form` `FormGroup` `Input` `Textarea` `Select` `SelectSearch` `SelectMultiple` `Checkbox` `CheckboxGroup` `Radio` `RadioGroup` `RadioCards` `Switch` `Toggle`

### Form Components - Advanced
`DatePicker` `DateRangePicker` `TimePicker` `DateTimePicker` `ColorPicker` `FilePicker` `ImagePicker` `FileDropzone` `RichTextEditor` `MarkdownEditor` `CodeEditor` `TagInput` `SliderInput` `RangeInput` `RatingInput` `PasswordInput` `SearchInput` `PhoneInput` `CurrencyInput` `NumberInput`

### Data Display
`DataTable` `Table` `Pagination` `List` `DescriptionList` `Tree` `Timeline` `Calendar` `KanbanBoard`

### Feedback
`Alert` `Toast` `ProgressBar` `EmptyState` `ErrorState`

### Overlays
`Modal` `ConfirmModal` `Drawer` `Dropdown` `Popover` `ContextMenu`

### Containers
`Card` `CardGrid` `Accordion` `Tabs` `Collapse` `Panel`

### Navigation
`NavLink` `NavGroup` `StepWizard` `BackButton` `CommandPalette`

### Charts
`Chart` `LineChart` `AreaChart` `BarChart` `DonutChart` `PieChart` `StatCard` `StatGrid` `MiniChart`

### Utilities
`CopyButton` `ClickToCopy` `ExternalLink` `Highlight` `RelativeTime` `CountUp` `Kbd`

## CLI Commands

CamboAdmin includes powerful Artisan commands to accelerate your development:

```bash
# Install CamboAdmin with interactive prompts
php artisan cambo:install

# Generate a complete CRUD with model, controller, and views
php artisan cambo:crud Product --fields="name:string,price:decimal,active:boolean"

# Generate a new page
php artisan cambo:page Reports/Monthly

# Generate a custom component
php artisan cambo:component Charts/RevenueChart

# Add a new module
php artisan cambo:add notifications
```

## Sponsors

<div class="sponsors">
  <p>Help support CamboAdmin development by becoming a sponsor.</p>
  <a href="https://github.com/sponsors/cambosoftware" class="sponsor-link">Become a Sponsor</a>
</div>

## For AI Assistants

Using an AI assistant like Claude, ChatGPT, or Copilot? Point it to our AI-optimized documentation:

- **[llms.txt](/llms.txt)** - Quick reference index
- **[llms-full.txt](/llms-full.txt)** - Complete documentation with all components, props, and examples

Example prompt: *"Read https://cambo-admin.cambosoftware.com/llms-full.txt and help me create a Laravel admin panel with CamboAdmin"*

## Community

Join our growing community of developers using CamboAdmin:

- [GitHub Discussions](https://github.com/cambosoftware/cambo-admin/discussions) - Ask questions and share ideas
- [GitHub Issues](https://github.com/cambosoftware/cambo-admin/issues) - Report bugs and request features
- [Discord Server](https://discord.gg/cambosoftware) - Real-time chat with the community

## License

CamboAdmin is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT). You are free to use it in personal and commercial projects.

<style>
.hero-badges {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 8px;
  margin: 2rem 0;
}
.hero-badges a {
  display: inline-block;
}
.sponsors {
  text-align: center;
  padding: 2rem;
  background: var(--c-bg-light);
  border-radius: 8px;
  margin: 2rem 0;
}
.sponsor-link {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: var(--c-brand);
  color: white !important;
  border-radius: 6px;
  font-weight: 600;
  text-decoration: none;
}
.sponsor-link:hover {
  background: var(--c-brand-light);
}
</style>
