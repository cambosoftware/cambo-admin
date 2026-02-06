<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'group' => 'general',
                'key' => 'app_name',
                'label' => 'Application Name',
                'description' => 'The name displayed in the interface',
                'type' => 'text',
                'default_value' => 'CamboAdmin',
                'is_public' => true,
                'order' => 1,
            ],
            [
                'group' => 'general',
                'key' => 'app_description',
                'label' => 'Description',
                'description' => 'Short description of the application',
                'type' => 'textarea',
                'default_value' => 'Modern admin panel',
                'is_public' => true,
                'order' => 2,
            ],
            [
                'group' => 'general',
                'key' => 'app_timezone',
                'label' => 'Timezone',
                'type' => 'select',
                'default_value' => 'UTC',
                'options' => [
                    ['value' => 'UTC', 'label' => 'UTC'],
                    ['value' => 'Europe/Paris', 'label' => 'Paris (UTC+1)'],
                    ['value' => 'Europe/London', 'label' => 'London (UTC+0)'],
                    ['value' => 'America/New_York', 'label' => 'New York (UTC-5)'],
                    ['value' => 'Asia/Tokyo', 'label' => 'Tokyo (UTC+9)'],
                ],
                'order' => 3,
            ],
            [
                'group' => 'general',
                'key' => 'app_locale',
                'label' => 'Default Language',
                'type' => 'select',
                'default_value' => 'en',
                'options' => [
                    ['value' => 'en', 'label' => 'English'],
                    ['value' => 'fr', 'label' => 'French'],
                    ['value' => 'es', 'label' => 'Spanish'],
                    ['value' => 'de', 'label' => 'German'],
                ],
                'is_public' => true,
                'order' => 4,
            ],
            [
                'group' => 'general',
                'key' => 'maintenance_mode',
                'label' => 'Maintenance Mode',
                'description' => 'Enable maintenance mode',
                'type' => 'boolean',
                'default_value' => '0',
                'order' => 5,
            ],

            // Appearance
            [
                'group' => 'appearance',
                'key' => 'primary_color',
                'label' => 'Primary Color',
                'description' => 'Brand color',
                'type' => 'color',
                'default_value' => '#6366f1',
                'is_public' => true,
                'order' => 1,
            ],
            [
                'group' => 'appearance',
                'key' => 'logo_path',
                'label' => 'Logo',
                'description' => 'Application logo',
                'type' => 'file',
                'is_public' => true,
                'order' => 2,
            ],
            [
                'group' => 'appearance',
                'key' => 'favicon_path',
                'label' => 'Favicon',
                'description' => 'Browser icon',
                'type' => 'file',
                'is_public' => true,
                'order' => 3,
            ],
            [
                'group' => 'appearance',
                'key' => 'dark_mode_default',
                'label' => 'Dark mode by default',
                'type' => 'boolean',
                'default_value' => '0',
                'is_public' => true,
                'order' => 4,
            ],
            [
                'group' => 'appearance',
                'key' => 'sidebar_collapsed',
                'label' => 'Sidebar collapsed by default',
                'type' => 'boolean',
                'default_value' => '0',
                'is_public' => true,
                'order' => 5,
            ],

            // Email
            [
                'group' => 'email',
                'key' => 'mail_from_address',
                'label' => 'From Address',
                'type' => 'text',
                'default_value' => 'noreply@example.com',
                'validation' => ['email'],
                'order' => 1,
            ],
            [
                'group' => 'email',
                'key' => 'mail_from_name',
                'label' => 'From Name',
                'type' => 'text',
                'default_value' => 'CamboAdmin',
                'order' => 2,
            ],
            [
                'group' => 'email',
                'key' => 'mail_driver',
                'label' => 'Email Driver',
                'type' => 'select',
                'default_value' => 'smtp',
                'options' => [
                    ['value' => 'smtp', 'label' => 'SMTP'],
                    ['value' => 'mailgun', 'label' => 'Mailgun'],
                    ['value' => 'ses', 'label' => 'Amazon SES'],
                    ['value' => 'postmark', 'label' => 'Postmark'],
                ],
                'order' => 3,
            ],

            // Security
            [
                'group' => 'security',
                'key' => 'password_min_length',
                'label' => 'Min password length',
                'type' => 'number',
                'default_value' => '8',
                'order' => 1,
            ],
            [
                'group' => 'security',
                'key' => 'session_lifetime',
                'label' => 'Session lifetime (minutes)',
                'type' => 'number',
                'default_value' => '120',
                'order' => 2,
            ],
            [
                'group' => 'security',
                'key' => 'two_factor_enabled',
                'label' => 'Enable 2FA',
                'description' => 'Allow two-factor authentication',
                'type' => 'boolean',
                'default_value' => '1',
                'order' => 3,
            ],
            [
                'group' => 'security',
                'key' => 'login_throttle_attempts',
                'label' => 'Max login attempts',
                'type' => 'number',
                'default_value' => '5',
                'order' => 4,
            ],
            [
                'group' => 'security',
                'key' => 'login_throttle_decay',
                'label' => 'Lockout after failures (min)',
                'type' => 'number',
                'default_value' => '1',
                'order' => 5,
            ],

            // Integrations
            [
                'group' => 'integrations',
                'key' => 'google_analytics_id',
                'label' => 'Google Analytics ID',
                'description' => 'E.g.: G-XXXXXXXXXX',
                'type' => 'text',
                'order' => 1,
            ],
            [
                'group' => 'integrations',
                'key' => 'recaptcha_site_key',
                'label' => 'reCAPTCHA Site Key',
                'type' => 'text',
                'order' => 2,
            ],
            [
                'group' => 'integrations',
                'key' => 'recaptcha_secret_key',
                'label' => 'reCAPTCHA Secret Key',
                'type' => 'text',
                'is_encrypted' => true,
                'order' => 3,
            ],

            // Advanced
            [
                'group' => 'advanced',
                'key' => 'debug_mode',
                'label' => 'Debug mode',
                'description' => 'Show detailed errors',
                'type' => 'boolean',
                'default_value' => '0',
                'order' => 1,
            ],
            [
                'group' => 'advanced',
                'key' => 'cache_enabled',
                'label' => 'Cache enabled',
                'type' => 'boolean',
                'default_value' => '1',
                'order' => 2,
            ],
            [
                'group' => 'advanced',
                'key' => 'api_rate_limit',
                'label' => 'API rate limit (req/min)',
                'type' => 'number',
                'default_value' => '60',
                'order' => 3,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::define($setting);
        }
    }
}
