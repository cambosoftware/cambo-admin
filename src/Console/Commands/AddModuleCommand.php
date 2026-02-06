<?php

namespace CamboSoftware\CamboAdmin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AddModuleCommand extends Command
{
    protected $signature = 'cambo:add
                            {module : The module to add (auth, roles, notifications, etc.)}
                            {--force : Overwrite existing files}';

    protected $description = 'Add a CamboAdmin module after installation';

    protected Filesystem $files;

    protected array $modules = [
        'auth' => [
            'description' => 'Authentication (login, register, 2FA)',
            'migrations' => ['add_two_factor_columns_to_users_table'],
            'pages' => ['Auth'],
            'controllers' => ['Auth'],
        ],
        'users' => [
            'description' => 'User management',
            'pages' => ['Users'],
            'requires' => ['auth'],
        ],
        'roles' => [
            'description' => 'Role management',
            'migrations' => ['create_roles_and_permissions_tables'],
            'pages' => ['Roles'],
            'seeders' => ['RolesAndPermissionsSeeder'],
            'requires' => ['auth'],
        ],
        'notifications' => [
            'description' => 'Notification center',
            'migrations' => ['create_notifications_table'],
            'pages' => ['Notifications'],
            'requires' => ['auth'],
        ],
        'activity-log' => [
            'description' => 'Activity logging',
            'migrations' => ['create_activity_logs_table'],
            'pages' => ['ActivityLog'],
            'requires' => ['auth'],
        ],
        'dashboard' => [
            'description' => 'Customizable dashboard',
            'migrations' => ['create_dashboard_widgets_table'],
            'seeders' => ['WidgetTypesSeeder'],
            'requires' => ['auth'],
        ],
        'media' => [
            'description' => 'File manager',
            'migrations' => ['create_media_files_table'],
            'pages' => ['Media'],
            'requires' => ['auth'],
        ],
        'settings' => [
            'description' => 'Dynamic settings',
            'migrations' => ['create_settings_table'],
            'pages' => ['Settings'],
            'seeders' => ['SettingsSeeder'],
            'requires' => ['auth'],
        ],
        'import-export' => [
            'description' => 'Import/Export functionality',
            'pages' => ['ImportExport'],
        ],
        'i18n' => [
            'description' => 'Multi-language support',
            'pages' => ['Translations'],
            'lang' => true,
        ],
        'themes' => [
            'description' => 'Theme customization',
            'pages' => ['Theme'],
        ],
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $moduleName = $this->argument('module');

        if (!isset($this->modules[$moduleName])) {
            $this->error("Unknown module: {$moduleName}");
            $this->line('Available modules: ' . implode(', ', array_keys($this->modules)));
            return self::FAILURE;
        }

        $module = $this->modules[$moduleName];

        $this->info("Adding module: {$moduleName} ({$module['description']})");

        // Check dependencies
        if (isset($module['requires'])) {
            foreach ($module['requires'] as $required) {
                if (!$this->isModuleEnabled($required)) {
                    $this->warn("Module '{$moduleName}' requires '{$required}'. Installing it first...");
                    $this->call('cambo:add', ['module' => $required, '--force' => $this->option('force')]);
                }
            }
        }

        // Enable in config
        $this->enableModuleInConfig($moduleName);

        // Publish migrations if any
        if (isset($module['migrations'])) {
            $this->publishMigrations($module['migrations']);
        }

        // Publish pages if any
        if (isset($module['pages'])) {
            $this->publishPages($module['pages']);
        }

        // Publish seeders if any
        if (isset($module['seeders'])) {
            $this->publishSeeders($module['seeders']);
        }

        // Publish lang files if needed
        if (isset($module['lang']) && $module['lang']) {
            $this->call('vendor:publish', [
                '--tag' => 'cambo-admin-lang',
                '--force' => $this->option('force'),
            ]);
        }

        // Run migrations
        if ($this->confirm('Run migrations now?', true)) {
            $this->call('migrate');
        }

        // Run seeders if any
        if (isset($module['seeders']) && $this->confirm('Run seeders now?', true)) {
            foreach ($module['seeders'] as $seeder) {
                $this->call('db:seed', ['--class' => $seeder]);
            }
        }

        $this->newLine();
        $this->info("✓ Module '{$moduleName}' added successfully!");

        return self::SUCCESS;
    }

    protected function isModuleEnabled(string $module): bool
    {
        return config("cambo-admin.modules.{$module}", false);
    }

    protected function enableModuleInConfig(string $moduleName): void
    {
        $configPath = config_path('cambo-admin.php');

        if (!$this->files->exists($configPath)) {
            $this->warn('Config file not found. Publishing...');
            $this->call('vendor:publish', ['--tag' => 'cambo-admin-config']);
        }

        $content = $this->files->get($configPath);
        $content = preg_replace(
            "/'{$moduleName}'\s*=>\s*false/",
            "'{$moduleName}' => true",
            $content
        );
        $this->files->put($configPath, $content);

        $this->info("✓ Module enabled in config");
    }

    protected function publishMigrations(array $migrations): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-migrations',
            '--force' => $this->option('force'),
        ]);
        $this->info("✓ Migrations published");
    }

    protected function publishPages(array $pages): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-pages',
            '--force' => $this->option('force'),
        ]);
        $this->info("✓ Pages published");
    }

    protected function publishSeeders(array $seeders): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-seeders',
            '--force' => $this->option('force'),
        ]);
        $this->info("✓ Seeders published");
    }
}
