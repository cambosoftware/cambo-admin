<?php

namespace CamboSoftware\CamboAdmin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'cambo:install
                            {--full : Install all modules without prompts}
                            {--only=* : Install only specific modules}
                            {--no-migrate : Skip database migrations}
                            {--no-seed : Skip database seeding}
                            {--force : Overwrite existing files}';

    protected $description = 'Install CamboAdmin package';

    protected Filesystem $files;

    protected array $modules = [
        'auth' => 'Authentication (login, register, 2FA)',
        'users' => 'User management',
        'roles' => 'Role management',
        'permissions' => 'Granular permissions',
        'notifications' => 'Notification center',
        'activity-log' => 'Activity logging',
        'dashboard' => 'Customizable dashboard',
        'media' => 'File manager',
        'settings' => 'Dynamic settings',
        'import-export' => 'Import/Export',
        'i18n' => 'Multi-language support',
        'themes' => 'Theme customization',
    ];

    protected array $selectedModules = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->displayBanner();

        // Determine installation type
        if ($this->option('full')) {
            $this->selectedModules = array_keys($this->modules);
            $this->info('Installing all modules...');
        } elseif ($onlyModules = $this->option('only')) {
            $this->selectedModules = $onlyModules;
            $this->info('Installing selected modules: ' . implode(', ', $onlyModules));
        } else {
            $this->runInteractiveInstall();
        }

        // Execute installation steps
        $this->publishConfig();
        $this->publishAssets();
        $this->publishMigrations();
        $this->publishSeeders();

        if (!$this->option('no-migrate')) {
            $this->runMigrations();
        }

        if (!$this->option('no-seed')) {
            $this->runSeeders();
        }

        $this->updateFiles();
        $this->createAdminUser();

        $this->displaySuccess();

        return self::SUCCESS;
    }

    protected function displayBanner(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════╗');
        $this->line('║                                                           ║');
        $this->line('║   ██████╗ █████╗ ███╗   ███╗██████╗  ██████╗              ║');
        $this->line('║  ██╔════╝██╔══██╗████╗ ████║██╔══██╗██╔═══██╗             ║');
        $this->line('║  ██║     ███████║██╔████╔██║██████╔╝██║   ██║             ║');
        $this->line('║  ██║     ██╔══██║██║╚██╔╝██║██╔══██╗██║   ██║             ║');
        $this->line('║  ╚██████╗██║  ██║██║ ╚═╝ ██║██████╔╝╚██████╔╝             ║');
        $this->line('║   ╚═════╝╚═╝  ╚═╝╚═╝     ╚═╝╚═════╝  ╚═════╝              ║');
        $this->line('║                                                           ║');
        $this->line('║              CamboAdmin - Installation                    ║');
        $this->line('║                                                           ║');
        $this->line('╚═══════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    protected function runInteractiveInstall(): void
    {
        $installType = $this->choice(
            'What type of installation would you like?',
            [
                'full' => 'Complete (all modules) - Recommended',
                'custom' => 'Custom (choose modules)',
                'minimal' => 'Minimal (UI components only)',
            ],
            'full'
        );

        if ($installType === 'full') {
            $this->selectedModules = array_keys($this->modules);
        } elseif ($installType === 'minimal') {
            $this->selectedModules = [];
        } else {
            $this->selectedModules = $this->selectModules();
        }
    }

    protected function selectModules(): array
    {
        $selected = [];

        foreach ($this->modules as $key => $description) {
            if ($this->confirm("Install {$description}?", true)) {
                $selected[] = $key;
            }
        }

        return $selected;
    }

    protected function publishConfig(): void
    {
        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-config',
            '--force' => $this->option('force'),
        ]);

        // Update config with selected modules
        $configPath = config_path('cambo-admin.php');
        if ($this->files->exists($configPath)) {
            $config = $this->files->get($configPath);

            foreach ($this->modules as $module => $description) {
                $enabled = in_array($module, $this->selectedModules) ? 'true' : 'false';
                $config = preg_replace(
                    "/'{$module}'\s*=>\s*(true|false)/",
                    "'{$module}' => {$enabled}",
                    $config
                );
            }

            $this->files->put($configPath, $config);
        }

        $this->info('✓ Configuration published');
    }

    protected function publishAssets(): void
    {
        $this->info('Publishing assets...');

        // Always publish core components
        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-components',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-composables',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-plugins',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-views',
            '--force' => $this->option('force'),
        ]);

        // Publish controllers
        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-controllers',
            '--force' => $this->option('force'),
        ]);

        // Publish pages for selected modules
        if (!empty($this->selectedModules)) {
            $this->call('vendor:publish', [
                '--tag' => 'cambo-admin-pages',
                '--force' => $this->option('force'),
            ]);
        }

        // Publish lang files if i18n is enabled
        if (in_array('i18n', $this->selectedModules)) {
            $this->call('vendor:publish', [
                '--tag' => 'cambo-admin-lang',
                '--force' => $this->option('force'),
            ]);
        }

        $this->info('✓ Assets published');
    }

    protected function publishMigrations(): void
    {
        $this->info('Publishing migrations...');

        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-migrations',
            '--force' => $this->option('force'),
        ]);

        $this->info('✓ Migrations published');
    }

    protected function publishSeeders(): void
    {
        $this->info('Publishing seeders...');

        $this->call('vendor:publish', [
            '--tag' => 'cambo-admin-seeders',
            '--force' => $this->option('force'),
        ]);

        $this->info('✓ Seeders published');
    }

    protected function runMigrations(): void
    {
        $this->info('Running migrations...');

        $this->call('migrate', [
            '--force' => $this->option('force'),
        ]);

        $this->info('✓ Migrations completed');
    }

    protected function runSeeders(): void
    {
        $this->info('Running seeders...');

        $seeders = [
            'RolesAndPermissionsSeeder',
            'SettingsSeeder',
            'WidgetTypesSeeder',
        ];

        foreach ($seeders as $seeder) {
            $seederClass = "Database\\Seeders\\{$seeder}";
            if (class_exists($seederClass)) {
                $this->call('db:seed', ['--class' => $seeder]);
            }
        }

        $this->info('✓ Seeders completed');
    }

    protected function updateFiles(): void
    {
        $this->info('Updating configuration files...');

        $this->updateUserModel();
        $this->updateViteConfig();
        $this->updateTailwindConfig();
        $this->updatePackageJson();
        $this->updateAppJs();

        $this->info('✓ Configuration files updated');
    }

    protected function updateUserModel(): void
    {
        $userModelPath = app_path('Models/User.php');

        if (!$this->files->exists($userModelPath)) {
            return;
        }

        $content = $this->files->get($userModelPath);

        // Add HasRoles trait if roles module is enabled
        if (in_array('roles', $this->selectedModules)) {
            if (!Str::contains($content, 'use HasRoles')) {
                $content = str_replace(
                    'use HasFactory, Notifiable;',
                    "use HasFactory, Notifiable;\n    use \\CamboSoftware\\CamboAdmin\\Models\\Traits\\HasRoles;",
                    $content
                );
            }
        }

        // Add LogsActivity trait if activity-log module is enabled
        if (in_array('activity-log', $this->selectedModules)) {
            if (!Str::contains($content, 'use LogsActivity')) {
                $content = str_replace(
                    'use HasFactory, Notifiable;',
                    "use HasFactory, Notifiable;\n    use \\CamboSoftware\\CamboAdmin\\Models\\Traits\\LogsActivity;",
                    $content
                );
            }
        }

        $this->files->put($userModelPath, $content);
    }

    protected function updateViteConfig(): void
    {
        $vitePath = base_path('vite.config.js');

        // Always create a complete vite.config.js for Vue + Inertia
        $viteConfig = <<<'JS'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
JS;

        $this->files->put($vitePath, $viteConfig);
    }

    protected function updateTailwindConfig(): void
    {
        $tailwindPath = base_path('tailwind.config.js');

        if (!$this->files->exists($tailwindPath)) {
            return;
        }

        $content = $this->files->get($tailwindPath);

        // Check if content paths are already set
        if (Str::contains($content, './resources/js/**/*.vue')) {
            return;
        }

        // Update content paths
        $content = preg_replace(
            "/(content:\s*\[)([^\]]*?)(\])/s",
            "$1\n        './resources/**/*.blade.php',\n        './resources/**/*.js',\n        './resources/**/*.vue',\n    $3",
            $content
        );

        $this->files->put($tailwindPath, $content);
    }

    protected function updatePackageJson(): void
    {
        $packagePath = base_path('package.json');

        if (!$this->files->exists($packagePath)) {
            return;
        }

        $package = json_decode($this->files->get($packagePath), true);

        // Required dev dependencies for Vue + Inertia + Tailwind + Icons + Charts
        $requiredDevDependencies = [
            '@heroicons/vue' => '^2.2',
            '@inertiajs/vue3' => '^2.0',
            '@vitejs/plugin-vue' => '^6.0',
            'chart.js' => '^4.4',
            'vue' => '^3.5',
            'vue-chartjs' => '^5.3',
        ];

        // Merge dependencies
        foreach ($requiredDevDependencies as $dep => $version) {
            if (!isset($package['devDependencies'][$dep])) {
                $package['devDependencies'][$dep] = $version;
            }
        }

        // Sort devDependencies alphabetically
        ksort($package['devDependencies']);

        $this->files->put(
            $packagePath,
            json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );

        $this->info('✓ package.json updated with Vue dependencies');
    }

    protected function updateAppJs(): void
    {
        $appJsPath = resource_path('js/app.js');

        if (!$this->files->exists($appJsPath)) {
            $this->createAppJs();
            return;
        }

        // Check if already configured
        $content = $this->files->get($appJsPath);
        if (Str::contains($content, 'resolvePageComponent')) {
            return;
        }

        $this->createAppJs();
    }

    protected function createAppJs(): void
    {
        $appJsContent = <<<'JS'
import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'CamboAdmin';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#6366f1',
    },
});
JS;

        $this->files->put(resource_path('js/app.js'), $appJsContent);
    }

    protected function createAdminUser(): void
    {
        if (!in_array('auth', $this->selectedModules)) {
            return;
        }

        if (!$this->confirm('Would you like to create an admin user?', true)) {
            return;
        }

        $email = $this->ask('Admin email', 'admin@example.com');
        $password = $this->secret('Admin password (min 8 characters)') ?? 'password';
        $name = $this->ask('Admin name', 'Admin');

        $userModel = config('cambo-admin.models.user') ?? \App\Models\User::class;

        if (!class_exists($userModel)) {
            $this->error('User model not found.');
            return;
        }

        try {
            $user = $userModel::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Assign admin role if roles module is enabled
            if (in_array('roles', $this->selectedModules) && method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            $this->info("✓ Admin user created: {$email}");
        } catch (\Exception $e) {
            $this->warn("Could not create admin user: {$e->getMessage()}");
        }
    }

    protected function displaySuccess(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════╗');
        $this->line('║                                                           ║');
        $this->line('║   ✓ CamboAdmin installed successfully!                    ║');
        $this->line('║                                                           ║');
        $this->line('╠═══════════════════════════════════════════════════════════╣');
        $this->line('║                                                           ║');
        $this->line('║   Next steps:                                             ║');
        $this->line('║                                                           ║');
        $this->line('║   1. Run: npm install                                     ║');
        $this->line('║   2. Run: npm run build                                   ║');
        $this->line('║   3. Visit: ' . url('/') . str_repeat(' ', 32 - strlen(url('/'))) . '║');
        $this->line('║                                                           ║');
        $this->line('║   Documentation: https://cambosoftware.com/docs           ║');
        $this->line('║                                                           ║');
        $this->line('╚═══════════════════════════════════════════════════════════╝');
        $this->newLine();
    }
}
