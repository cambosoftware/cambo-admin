<?php

namespace CamboSoftware\CamboAdmin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PDO;
use PDOException;

class InstallCommand extends Command
{
    protected $signature = 'cambo:install
                            {--full : Install all modules without prompts}
                            {--only=* : Install only specific modules}
                            {--no-migrate : Skip database migrations}
                            {--no-seed : Skip database seeding}
                            {--no-db-setup : Skip database configuration check}
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

        // Check and fix directory permissions
        $this->ensureDirectoryPermissions();

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

        // Database configuration and migrations
        if (!$this->option('no-migrate')) {
            if (!$this->option('no-db-setup')) {
                $this->ensureDatabaseIsConfigured();
            }
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

    protected function ensureDirectoryPermissions(): void
    {
        $this->info('Checking directory permissions...');

        $directories = [
            storage_path(),
            storage_path('app'),
            storage_path('app/public'),
            storage_path('framework'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        $issues = [];

        foreach ($directories as $directory) {
            // Create directory if it doesn't exist
            if (!$this->files->isDirectory($directory)) {
                try {
                    $this->files->makeDirectory($directory, 0775, true);
                } catch (\Exception $e) {
                    $issues[] = $directory;
                    continue;
                }
            }

            // Check if writable
            if (!$this->files->isWritable($directory)) {
                // Try to fix permissions
                try {
                    chmod($directory, 0775);
                    if (!$this->files->isWritable($directory)) {
                        $issues[] = $directory;
                    }
                } catch (\Exception $e) {
                    $issues[] = $directory;
                }
            }
        }

        if (empty($issues)) {
            $this->info('✓ Directory permissions OK');
            return;
        }

        $this->warn('⚠ Some directories are not writable:');
        foreach ($issues as $dir) {
            $this->line("  - {$dir}");
        }

        $this->newLine();
        $this->line('To fix this, run the following commands:');
        $this->newLine();
        $this->line('  <fg=yellow>chmod -R 775 storage bootstrap/cache</>');
        $this->line('  <fg=yellow>chown -R www-data:www-data storage bootstrap/cache</>');
        $this->newLine();

        if (!$this->confirm('Continue installation anyway?', true)) {
            $this->error('Installation aborted. Please fix permissions and try again.');
            exit(1);
        }
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

    protected function ensureDatabaseIsConfigured(): void
    {
        $this->info('Checking database connection...');

        if ($this->checkDatabaseConnection()) {
            $this->info('✓ Database connection successful');
            return;
        }

        $this->warn('⚠ Database connection failed.');
        $this->newLine();

        if (!$this->confirm('Would you like to configure the database now?', true)) {
            $this->error('Database configuration is required for migrations.');
            $this->info('You can run the installer again with --no-migrate to skip migrations.');
            exit(1);
        }

        $this->configureDatabaseInteractively();
    }

    protected function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function configureDatabaseInteractively(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════╗');
        $this->line('║              Database Configuration                       ║');
        $this->line('╚═══════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Choose database driver
        $driver = $this->choice(
            'Which database would you like to use?',
            [
                'mysql' => 'MySQL / MariaDB (Recommended)',
                'pgsql' => 'PostgreSQL',
                'sqlite' => 'SQLite (Simple, file-based)',
            ],
            'mysql'
        );

        if ($driver === 'sqlite') {
            $this->configureSqlite();
        } else {
            $this->configureServerDatabase($driver);
        }

        // Clear config cache and reconnect
        $this->call('config:clear', [], $this->output);

        // Verify connection
        if ($this->checkDatabaseConnection()) {
            $this->info('✓ Database configured successfully!');
        } else {
            $this->error('Database connection still failing. Please check your credentials.');
            exit(1);
        }
    }

    protected function configureSqlite(): void
    {
        $dbPath = database_path('database.sqlite');

        if (!$this->files->exists($dbPath)) {
            $this->files->put($dbPath, '');
            $this->info("Created SQLite database: {$dbPath}");
        }

        $this->updateEnvValue('DB_CONNECTION', 'sqlite');
        $this->updateEnvValue('DB_DATABASE', $dbPath);

        // Remove unused MySQL settings
        $this->removeEnvKeys(['DB_HOST', 'DB_PORT', 'DB_USERNAME', 'DB_PASSWORD']);
    }

    protected function configureServerDatabase(string $driver): void
    {
        $defaultPort = $driver === 'mysql' ? '3306' : '5432';
        $driverName = $driver === 'mysql' ? 'MySQL' : 'PostgreSQL';

        $this->info("Configuring {$driverName} database...");
        $this->newLine();

        // Collect database settings
        $host = $this->ask('Database host', '127.0.0.1');
        $port = $this->ask('Database port', $defaultPort);
        $database = $this->ask('Database name', 'cambo_admin');
        $username = $this->ask('Database username', 'root');
        $password = $this->secret('Database password (leave empty for none)') ?? '';

        // Update .env file
        $this->updateEnvValue('DB_CONNECTION', $driver);
        $this->updateEnvValue('DB_HOST', $host);
        $this->updateEnvValue('DB_PORT', $port);
        $this->updateEnvValue('DB_DATABASE', $database);
        $this->updateEnvValue('DB_USERNAME', $username);
        $this->updateEnvValue('DB_PASSWORD', $password);

        // Try to create database if it doesn't exist
        $this->createDatabaseIfNotExists($driver, $host, $port, $database, $username, $password);
    }

    protected function createDatabaseIfNotExists(
        string $driver,
        string $host,
        string $port,
        string $database,
        string $username,
        string $password
    ): void {
        $this->info("Checking if database '{$database}' exists...");

        try {
            $dsn = $driver === 'mysql'
                ? "mysql:host={$host};port={$port}"
                : "pgsql:host={$host};port={$port}";

            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if database exists
            if ($driver === 'mysql') {
                $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
            } else {
                $stmt = $pdo->query("SELECT datname FROM pg_database WHERE datname = '{$database}'");
            }

            if ($stmt->fetch()) {
                $this->info("✓ Database '{$database}' already exists");
                return;
            }

            // Ask to create database
            if ($this->confirm("Database '{$database}' does not exist. Create it now?", true)) {
                $charset = $driver === 'mysql' ? 'utf8mb4' : 'UTF8';
                $collate = $driver === 'mysql' ? 'utf8mb4_unicode_ci' : '';

                if ($driver === 'mysql') {
                    $pdo->exec("CREATE DATABASE `{$database}` CHARACTER SET {$charset} COLLATE {$collate}");
                } else {
                    $pdo->exec("CREATE DATABASE \"{$database}\" ENCODING '{$charset}'");
                }

                $this->info("✓ Database '{$database}' created successfully");
            }
        } catch (PDOException $e) {
            $this->warn("Could not create database automatically: " . $e->getMessage());
            $this->info("Please create the database manually and run the installer again.");
        }
    }

    protected function updateEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (!$this->files->exists($envPath)) {
            $this->files->copy(base_path('.env.example'), $envPath);
        }

        $content = $this->files->get($envPath);

        // Escape value if it contains spaces or special characters
        if (preg_match('/\s|[#"\'\\\\]/', $value)) {
            $value = '"' . addslashes($value) . '"';
        }

        // Check if key exists
        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        $this->files->put($envPath, $content);
    }

    protected function removeEnvKeys(array $keys): void
    {
        $envPath = base_path('.env');

        if (!$this->files->exists($envPath)) {
            return;
        }

        $content = $this->files->get($envPath);

        foreach ($keys as $key) {
            $content = preg_replace("/^{$key}=.*\n?/m", '', $content);
        }

        $this->files->put($envPath, $content);
    }

    protected function updateFiles(): void
    {
        $this->info('Updating configuration files...');

        $this->updateUserModel();
        $this->updateViteConfig();
        $this->updateTailwindConfig();
        $this->updatePackageJson();
        $this->updateAppJs();
        $this->createInertiaMiddleware();
        $this->updateBootstrapApp();
        $this->updateWebRoutes();

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

    protected function createInertiaMiddleware(): void
    {
        $middlewarePath = app_path('Http/Middleware/HandleInertiaRequests.php');

        // Skip if already exists
        if ($this->files->exists($middlewarePath) && !$this->option('force')) {
            return;
        }

        // Ensure directory exists
        $this->files->ensureDirectoryExists(app_path('Http/Middleware'));

        $middlewareContent = <<<'PHP'
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'permissions' => $request->user()?->getAllPermissions() ?? [],
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
PHP;

        $this->files->put($middlewarePath, $middlewareContent);
        $this->info('✓ HandleInertiaRequests middleware created');
    }

    protected function updateBootstrapApp(): void
    {
        $bootstrapPath = base_path('bootstrap/app.php');

        if (!$this->files->exists($bootstrapPath)) {
            return;
        }

        $content = $this->files->get($bootstrapPath);

        // Check if HandleInertiaRequests is already configured
        if (Str::contains($content, 'HandleInertiaRequests')) {
            return;
        }

        // For Laravel 11+, add middleware to withMiddleware callback
        if (Str::contains($content, '->withMiddleware(function (Middleware $middleware)')) {
            // Check if there's already web middleware append
            if (Str::contains($content, '$middleware->web(append:')) {
                // Add our middleware to existing append
                $content = preg_replace(
                    '/(\$middleware->web\(append:\s*\[)/',
                    "$1\n            \\App\\Http\\Middleware\\HandleInertiaRequests::class,",
                    $content
                );
            } else {
                // Add new web middleware append
                $content = preg_replace(
                    '/(->withMiddleware\(function \(Middleware \$middleware\).*?{)/s',
                    "$1\n        \$middleware->web(append: [\n            \\App\\Http\\Middleware\\HandleInertiaRequests::class,\n        ]);",
                    $content
                );
            }

            $this->files->put($bootstrapPath, $content);
            $this->info('✓ HandleInertiaRequests middleware registered in bootstrap/app.php');
        }
    }

    protected function updateWebRoutes(): void
    {
        $routesPath = base_path('routes/web.php');

        if (!$this->files->exists($routesPath)) {
            return;
        }

        $content = $this->files->get($routesPath);

        // Check if redirect to cambo.dashboard is already set
        if (Str::contains($content, 'cambo.dashboard')) {
            return;
        }

        // Replace default Laravel welcome route with redirect to admin dashboard
        if (Str::contains($content, "return view('welcome')")) {
            $content = preg_replace(
                "/Route::get\('\/'\s*,\s*function\s*\(\)\s*\{[\s\S]*?return view\('welcome'\);[\s\S]*?\}\);/",
                "// Redirect home to admin dashboard\nRoute::get('/', function () {\n    return redirect()->route('cambo.dashboard');\n});",
                $content
            );

            $this->files->put($routesPath, $content);
            $this->info('✓ Default route redirects to admin dashboard');
        }
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
