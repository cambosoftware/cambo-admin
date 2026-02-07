<?php

namespace Database\Seeders;

use CamboSoftware\CamboAdmin\Models\WidgetType;
use Illuminate\Database\Seeder;

class WidgetTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $widgets = [
            [
                'slug' => 'stats-card',
                'name' => 'Carte statistique',
                'description' => 'Affiche une statistique clé avec icône et variation',
                'component' => 'WidgetStatsCard',
                'icon' => 'chart-bar',
                'default_config' => [
                    'title' => 'Statistique',
                    'stat_key' => 'users',
                    'icon' => 'users',
                    'color' => 'primary',
                    'show_trend' => true,
                ],
                'config_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'stat_key' => ['type' => 'string', 'enum' => ['users', 'orders', 'revenue', 'products']],
                        'icon' => ['type' => 'string'],
                        'color' => ['type' => 'string', 'enum' => ['primary', 'success', 'warning', 'danger', 'info']],
                        'show_trend' => ['type' => 'boolean'],
                    ],
                ],
                'min_width' => 2,
                'min_height' => 1,
                'default_width' => 3,
                'default_height' => 1,
            ],
            [
                'slug' => 'chart-line',
                'name' => 'Graphique linéaire',
                'description' => 'Affiche une évolution dans le temps',
                'component' => 'WidgetChartLine',
                'icon' => 'presentation-chart-line',
                'default_config' => [
                    'title' => 'Évolution',
                    'data_source' => 'sales',
                    'period' => 'month',
                ],
                'min_width' => 4,
                'min_height' => 2,
                'default_width' => 8,
                'default_height' => 2,
            ],
            [
                'slug' => 'chart-bar',
                'name' => 'Graphique en barres',
                'description' => 'Affiche des données comparatives',
                'component' => 'WidgetChartBar',
                'icon' => 'chart-bar',
                'default_config' => [
                    'title' => 'Comparaison',
                    'data_source' => 'categories',
                ],
                'min_width' => 4,
                'min_height' => 2,
                'default_width' => 6,
                'default_height' => 2,
            ],
            [
                'slug' => 'chart-pie',
                'name' => 'Graphique circulaire',
                'description' => 'Affiche une répartition',
                'component' => 'WidgetChartPie',
                'icon' => 'chart-pie',
                'default_config' => [
                    'title' => 'Répartition',
                    'data_source' => 'status',
                ],
                'min_width' => 3,
                'min_height' => 2,
                'default_width' => 4,
                'default_height' => 2,
            ],
            [
                'slug' => 'recent-activity',
                'name' => 'Activité récente',
                'description' => 'Liste les dernières actions',
                'component' => 'WidgetRecentActivity',
                'icon' => 'clock',
                'default_config' => [
                    'title' => 'Activité récente',
                    'limit' => 5,
                ],
                'min_width' => 3,
                'min_height' => 2,
                'default_width' => 4,
                'default_height' => 2,
            ],
            [
                'slug' => 'quick-links',
                'name' => 'Raccourcis',
                'description' => 'Liens rapides vers des pages',
                'component' => 'WidgetQuickLinks',
                'icon' => 'link',
                'default_config' => [
                    'title' => 'Raccourcis',
                    'links' => [
                        ['label' => 'Utilisateurs', 'url' => '/users', 'icon' => 'users'],
                        ['label' => 'Rôles', 'url' => '/roles', 'icon' => 'shield-check'],
                    ],
                ],
                'min_width' => 2,
                'min_height' => 1,
                'default_width' => 3,
                'default_height' => 2,
            ],
            [
                'slug' => 'data-table',
                'name' => 'Tableau de données',
                'description' => 'Affiche un tableau compact de données',
                'component' => 'WidgetDataTable',
                'icon' => 'table-cells',
                'default_config' => [
                    'title' => 'Dernières entrées',
                    'data_source' => 'users',
                    'limit' => 5,
                    'columns' => ['name', 'email', 'created_at'],
                ],
                'min_width' => 4,
                'min_height' => 2,
                'default_width' => 6,
                'default_height' => 2,
            ],
            [
                'slug' => 'welcome',
                'name' => 'Message de bienvenue',
                'description' => 'Un message personnalisé',
                'component' => 'WidgetWelcome',
                'icon' => 'hand-raised',
                'default_config' => [
                    'title' => 'Bienvenue',
                    'message' => 'Bienvenue sur votre tableau de bord personnalisé.',
                ],
                'min_width' => 4,
                'min_height' => 1,
                'default_width' => 12,
                'default_height' => 1,
            ],
            [
                'slug' => 'calendar',
                'name' => 'Mini calendrier',
                'description' => 'Affiche un calendrier compact',
                'component' => 'WidgetCalendar',
                'icon' => 'calendar',
                'default_config' => [
                    'title' => 'Calendrier',
                    'show_events' => true,
                ],
                'min_width' => 3,
                'min_height' => 2,
                'default_width' => 4,
                'default_height' => 3,
            ],
            [
                'slug' => 'notes',
                'name' => 'Notes',
                'description' => 'Bloc-notes personnel',
                'component' => 'WidgetNotes',
                'icon' => 'document-text',
                'default_config' => [
                    'title' => 'Mes notes',
                    'content' => '',
                ],
                'min_width' => 2,
                'min_height' => 2,
                'default_width' => 3,
                'default_height' => 2,
            ],
        ];

        foreach ($widgets as $widget) {
            WidgetType::register($widget);
        }
    }
}
