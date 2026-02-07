import { defaultTheme } from '@vuepress/theme-default'
import { defineUserConfig } from 'vuepress'
import { viteBundler } from '@vuepress/bundler-vite'
import { searchPlugin } from '@vuepress/plugin-search'
import { prismjsPlugin } from '@vuepress/plugin-prismjs'
import { sitemapPlugin } from '@vuepress/plugin-sitemap'

export default defineUserConfig({
  base: '/',
  lang: 'en-US',
  title: 'CamboAdmin',
  description: 'A complete Laravel backoffice package with 150+ Vue.js components. Build beautiful admin panels in minutes with authentication, roles & permissions, CRUD generator, and more.',

  head: [
    // Favicon
    ['link', { rel: 'icon', href: '/images/favicon.ico' }],
    ['link', { rel: 'apple-touch-icon', sizes: '180x180', href: '/images/apple-touch-icon.png' }],

    // SEO Meta Tags
    ['meta', { name: 'theme-color', content: '#6366f1' }],
    ['meta', { name: 'author', content: 'CamboSoftware' }],
    ['meta', { name: 'keywords', content: 'laravel, admin panel, vue.js, inertia.js, crud generator, authentication, roles permissions, dashboard, backoffice, tailwind css' }],
    ['meta', { name: 'robots', content: 'index, follow' }],
    ['link', { rel: 'canonical', href: 'https://cambo-admin.cambosoftware.com' }],

    // Open Graph
    ['meta', { property: 'og:type', content: 'website' }],
    ['meta', { property: 'og:site_name', content: 'CamboAdmin' }],
    ['meta', { property: 'og:title', content: 'CamboAdmin - Laravel Admin Package' }],
    ['meta', { property: 'og:description', content: 'Build beautiful admin panels in minutes with 150+ Vue.js components, authentication, roles & permissions, and CRUD generator.' }],
    ['meta', { property: 'og:url', content: 'https://cambo-admin.cambosoftware.com' }],
    ['meta', { property: 'og:image', content: 'https://cambo-admin.cambosoftware.com/images/og-image.png' }],
    ['meta', { property: 'og:image:width', content: '1200' }],
    ['meta', { property: 'og:image:height', content: '630' }],
    ['meta', { property: 'og:locale', content: 'en_US' }],

    // Twitter Card
    ['meta', { name: 'twitter:card', content: 'summary_large_image' }],
    ['meta', { name: 'twitter:title', content: 'CamboAdmin - Laravel Admin Package' }],
    ['meta', { name: 'twitter:description', content: 'Build beautiful admin panels in minutes with 150+ Vue.js components.' }],
    ['meta', { name: 'twitter:image', content: 'https://cambo-admin.cambosoftware.com/images/og-image.png' }],

    // Google Analytics
    ['script', { async: true, src: 'https://www.googletagmanager.com/gtag/js?id=G-5HB9B47HTG' }],
    ['script', {}, `
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-5HB9B47HTG');
    `],
  ],

  bundler: viteBundler(),

  theme: defaultTheme({
    logo: '/images/logo-header.png',
    colorMode: 'light',
    colorModeSwitch: true,
    docsDir: 'docs',
    editLink: true,
    editLinkText: 'Edit this page on GitHub',
    lastUpdated: true,
    lastUpdatedText: 'Last Updated',
    contributors: true,
    contributorsText: 'Contributors',

    navbar: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/' },
      {
        text: 'Components',
        children: [
          { text: 'Overview', link: '/components/' },
          { text: 'Layout', link: '/components/layout/' },
          { text: 'UI', link: '/components/ui/' },
          { text: 'Forms', link: '/components/forms/' },
          { text: 'Data Display', link: '/components/data/' },
          { text: 'Feedback', link: '/components/feedback/' },
          { text: 'Overlays', link: '/components/overlays/' },
          { text: 'Navigation', link: '/components/navigation/' },
          { text: 'Charts', link: '/components/charts/' },
        ]
      },
      { text: 'Samples', link: '/samples/' },
      { text: 'API', link: '/api/' },
      {
        text: 'Links',
        children: [
          { text: 'GitHub', link: 'https://github.com/cambosoftware/cambo-admin' },
          { text: 'Packagist', link: 'https://packagist.org/packages/cambosoftware/cambo-admin' },
          { text: 'Changelog', link: '/changelog' },
        ],
      },
    ],

    sidebar: {
      '/guide/': [
        {
          text: 'Getting Started',
          collapsible: false,
          children: [
            '/guide/',
            '/guide/installation',
            '/guide/configuration',
            '/guide/quick-start',
          ],
        },
        {
          text: 'Features',
          collapsible: true,
          children: [
            '/guide/features/authentication',
            '/guide/features/roles-permissions',
            '/guide/features/notifications',
            '/guide/features/activity-log',
            '/guide/features/dashboard',
            '/guide/features/file-manager',
            '/guide/features/settings',
            '/guide/features/import-export',
            '/guide/features/i18n',
            '/guide/features/themes',
          ],
        },
        {
          text: 'CLI Commands',
          collapsible: true,
          children: [
            '/guide/cli/install',
            '/guide/cli/crud',
            '/guide/cli/page',
            '/guide/cli/component',
            '/guide/cli/add-module',
          ],
        },
        {
          text: 'Advanced',
          collapsible: true,
          children: [
            '/guide/advanced/customization',
            '/guide/advanced/extending',
            '/guide/advanced/security',
          ],
        },
      ],
      '/components/': [
        {
          text: 'Overview',
          link: '/components/',
        },
        {
          text: 'Layout',
          collapsible: true,
          children: [
            '/components/layout/admin-layout',
            '/components/layout/sidebar',
            '/components/layout/navbar',
            '/components/layout/breadcrumb',
            '/components/layout/page-header',
            '/components/layout/container',
          ],
        },
        {
          text: 'UI Components',
          collapsible: true,
          children: [
            '/components/ui/button',
            '/components/ui/button-group',
            '/components/ui/icon-button',
            '/components/ui/badge',
            '/components/ui/avatar',
            '/components/ui/avatar-group',
            '/components/ui/icon',
            '/components/ui/spinner',
            '/components/ui/skeleton',
            '/components/ui/tooltip',
            '/components/ui/divider',
            '/components/ui/app-link',
          ],
        },
        {
          text: 'Form - Basic',
          collapsible: true,
          children: [
            '/components/forms/form',
            '/components/forms/form-group',
            '/components/forms/input',
            '/components/forms/textarea',
            '/components/forms/select',
            '/components/forms/select-search',
            '/components/forms/select-multiple',
            '/components/forms/checkbox',
            '/components/forms/checkbox-group',
            '/components/forms/radio',
            '/components/forms/radio-group',
            '/components/forms/radio-cards',
            '/components/forms/switch',
            '/components/forms/toggle',
          ],
        },
        {
          text: 'Form - Advanced',
          collapsible: true,
          children: [
            '/components/forms/date-picker',
            '/components/forms/date-range-picker',
            '/components/forms/time-picker',
            '/components/forms/date-time-picker',
            '/components/forms/color-picker',
            '/components/forms/file-picker',
            '/components/forms/image-picker',
            '/components/forms/file-dropzone',
            '/components/forms/rich-text-editor',
            '/components/forms/markdown-editor',
            '/components/forms/code-editor',
            '/components/forms/tag-input',
            '/components/forms/slider-input',
            '/components/forms/range-input',
            '/components/forms/rating-input',
            '/components/forms/password-input',
            '/components/forms/search-input',
            '/components/forms/phone-input',
            '/components/forms/currency-input',
            '/components/forms/number-input',
          ],
        },
        {
          text: 'Data Display',
          collapsible: true,
          children: [
            '/components/data/data-table',
            '/components/data/table',
            '/components/data/pagination',
            '/components/data/list',
            '/components/data/description-list',
            '/components/data/tree',
            '/components/data/timeline',
            '/components/data/calendar',
            '/components/data/kanban-board',
          ],
        },
        {
          text: 'Feedback',
          collapsible: true,
          children: [
            '/components/feedback/alert',
            '/components/feedback/toast',
            '/components/feedback/progress-bar',
            '/components/feedback/empty-state',
            '/components/feedback/error-state',
          ],
        },
        {
          text: 'Overlays',
          collapsible: true,
          children: [
            '/components/overlays/modal',
            '/components/overlays/confirm-modal',
            '/components/overlays/drawer',
            '/components/overlays/dropdown',
            '/components/overlays/popover',
            '/components/overlays/context-menu',
          ],
        },
        {
          text: 'Containers',
          collapsible: true,
          children: [
            '/components/containers/card',
            '/components/containers/card-grid',
            '/components/containers/accordion',
            '/components/containers/tabs',
            '/components/containers/collapse',
            '/components/containers/panel',
          ],
        },
        {
          text: 'Navigation',
          collapsible: true,
          children: [
            '/components/navigation/nav-link',
            '/components/navigation/nav-group',
            '/components/navigation/step-wizard',
            '/components/navigation/back-button',
            '/components/navigation/command-palette',
          ],
        },
        {
          text: 'Charts',
          collapsible: true,
          children: [
            '/components/charts/chart',
            '/components/charts/line-chart',
            '/components/charts/area-chart',
            '/components/charts/bar-chart',
            '/components/charts/donut-chart',
            '/components/charts/pie-chart',
            '/components/charts/stat-card',
            '/components/charts/stat-grid',
            '/components/charts/mini-chart',
          ],
        },
        {
          text: 'Utilities',
          collapsible: true,
          children: [
            '/components/utilities/copy-button',
            '/components/utilities/click-to-copy',
            '/components/utilities/external-link',
            '/components/utilities/highlight',
            '/components/utilities/relative-time',
            '/components/utilities/count-up',
            '/components/utilities/kbd',
          ],
        },
      ],
      '/api/': [
        {
          text: 'Facade & Services',
          collapsible: false,
          children: [
            '/api/',
            '/api/facade',
            '/api/theme-service',
            '/api/translation-service',
            '/api/import-export-service',
          ],
        },
        {
          text: 'Models',
          collapsible: true,
          children: [
            '/api/models/role',
            '/api/models/permission',
            '/api/models/setting',
            '/api/models/activity',
            '/api/models/media-file',
          ],
        },
        {
          text: 'Traits',
          collapsible: true,
          children: [
            '/api/traits/has-roles',
            '/api/traits/logs-activity',
          ],
        },
        {
          text: 'Middleware',
          collapsible: true,
          children: [
            '/api/middleware/check-role',
            '/api/middleware/check-permission',
            '/api/middleware/set-locale',
          ],
        },
      ],
      '/samples/': [
        {
          text: 'Sample Pages',
          collapsible: false,
          children: [
            '/samples/',
          ],
        },
        {
          text: 'Authentication',
          collapsible: true,
          children: [
            '/samples/login',
            '/samples/register',
          ],
        },
        {
          text: 'Dashboard',
          collapsible: true,
          children: [
            '/samples/dashboard',
          ],
        },
        {
          text: 'CRUD Pages',
          collapsible: true,
          children: [
            '/samples/users/index',
            '/samples/users/create',
          ],
        },
        {
          text: 'Other Pages',
          collapsible: true,
          children: [
            '/samples/settings',
          ],
        },
      ],
    },
  }),

  plugins: [
    searchPlugin({
      locales: {
        '/': {
          placeholder: 'Search documentation...',
        },
      },
      maxSuggestions: 10,
    }),
    prismjsPlugin({
      themes: {
        light: 'one-light',
        dark: 'one-dark',
      },
    }),
    sitemapPlugin({
      hostname: 'https://cambo-admin.cambosoftware.com',
      changefreq: 'weekly',
    }),
  ],
})
