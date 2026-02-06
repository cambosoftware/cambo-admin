<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific modules. Each module can be toggled
    | independently. Some modules have dependencies on others.
    |
    */
    'modules' => [
        'auth' => true,           // Authentication (login, register, 2FA)
        'users' => true,          // User management CRUD
        'roles' => true,          // Role management (requires auth)
        'permissions' => true,    // Granular permissions (requires roles)
        'notifications' => true,  // Notification center
        'activity-log' => true,   // Activity logging
        'dashboard' => true,      // Customizable dashboard with widgets
        'media' => true,          // File manager
        'settings' => true,       // Dynamic settings
        'import-export' => true,  // Import/Export functionality
        'i18n' => true,           // Multi-language support
        'themes' => true,         // Theme customization
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Specify custom model classes. Set to null to use package defaults.
    | If you have your own User model, specify it here.
    |
    */
    'models' => [
        'user' => null, // null = App\Models\User::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Configure the routing behavior for the admin panel.
    |
    */
    'routes' => [
        'enabled' => true,
        'prefix' => 'admin',
        'middleware' => ['web', 'auth', 'verified'],
        'as' => 'cambo.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Appearance
    |--------------------------------------------------------------------------
    |
    | Customize the look and feel of your admin panel.
    |
    */
    'appearance' => [
        'name' => env('APP_NAME', 'CamboAdmin'),
        'logo' => null,
        'favicon' => null,
        'primary_color' => '#6366f1',
        'dark_mode' => 'auto', // 'light', 'dark', 'auto'
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Toggle specific features within modules.
    |
    */
    'features' => [
        'registration' => true,
        'password_reset' => true,
        'two_factor' => true,
        'email_verification' => true,
        'remember_me' => true,
        'social_login' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Configure sidebar behavior and appearance.
    |
    */
    'sidebar' => [
        'collapsible' => true,
        'collapsed_by_default' => true,
        'expand_on_hover' => true,
        'theme' => 'dark', // 'dark', 'light'
    ],

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    |
    | Default settings for data tables.
    |
    */
    'datatable' => [
        'per_page_options' => [10, 25, 50, 100],
        'default_per_page' => 25,
        'debounce_search' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Exports
    |--------------------------------------------------------------------------
    |
    | Configure export options. PDF requires barryvdh/laravel-dompdf.
    |
    */
    'exports' => [
        'csv' => true,
        'excel' => true,
        'pdf' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Supported locales for the admin panel.
    |
    */
    'locales' => [
        'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false],
        'fr' => ['name' => 'French', 'native' => 'Français', 'rtl' => false],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'rtl' => false],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migrations
    |--------------------------------------------------------------------------
    |
    | Control migration behavior.
    |
    */
    'migrations' => [
        'enabled' => true,
        'table_prefix' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media / File Manager
    |--------------------------------------------------------------------------
    |
    | Configure the file manager settings.
    |
    */
    'media' => [
        'disk' => 'public',
        'max_upload_size' => 10240, // KB
        'allowed_types' => [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'],
            'video' => ['mp4', 'avi', 'mov', 'wmv'],
            'audio' => ['mp3', 'wav', 'ogg'],
            'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    |
    | Configure activity logging behavior.
    |
    */
    'activity_log' => [
        'enabled' => true,
        'log_name' => 'default',
        'retention_days' => 90, // null = keep forever
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Configure notification settings.
    |
    */
    'notifications' => [
        'database' => true,
        'email' => true,
        'real_time' => false, // Requires Laravel Echo / Pusher
    ],

];
