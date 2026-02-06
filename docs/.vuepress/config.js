import { defaultTheme } from '@vuepress/theme-default'
import { defineUserConfig } from 'vuepress'
import { viteBundler } from '@vuepress/bundler-vite'
import { searchPlugin } from '@vuepress/plugin-search'
import { prismjsPlugin } from '@vuepress/plugin-prismjs'

export default defineUserConfig({
  base: '/cambo-admin/',
  lang: 'en-US',
  title: 'CamboAdmin',
  description: 'Official documentation for the CamboAdmin Laravel Package',

  head: [
    ['link', { rel: 'icon', href: '/images/favicon.ico' }],
    ['meta', { name: 'theme-color', content: '#3B82F6' }],
  ],

  bundler: viteBundler(),

  theme: defaultTheme({
    logo: '/images/logo.png',
    repo: 'cambosoftware/cambo-admin',
    docsDir: 'docs',
    editLink: true,
    editLinkText: 'Edit this page on GitHub',
    lastUpdated: true,
    lastUpdatedText: 'Last Updated',
    contributors: true,
    contributorsText: 'Contributors',

    navbar: [
      { text: 'Guide', link: '/guide/' },
      { text: 'Components', link: '/components/' },
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
          collapsible: true,
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
          text: 'CLI & Generators',
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
          text: 'Base UI',
          collapsible: true,
          children: [
            '/components/button',
            '/components/badge',
            '/components/avatar',
            '/components/spinner',
            '/components/skeleton',
            '/components/tooltip',
            '/components/divider',
          ],
        },
        {
          text: 'Forms',
          collapsible: true,
          children: [
            '/components/form',
            '/components/input',
            '/components/textarea',
            '/components/select',
            '/components/checkbox',
            '/components/radio',
            '/components/switch',
            '/components/file-input',
          ],
        },
        {
          text: 'Navigation',
          collapsible: true,
          children: [
            '/components/tabs',
            '/components/breadcrumb',
            '/components/pagination',
            '/components/dropdown',
          ],
        },
        {
          text: 'Feedback',
          collapsible: true,
          children: [
            '/components/alert',
            '/components/toast',
            '/components/modal',
            '/components/drawer',
            '/components/progress',
          ],
        },
        {
          text: 'Data Display',
          collapsible: true,
          children: [
            '/components/table',
            '/components/card',
            '/components/accordion',
            '/components/stats',
          ],
        },
      ],
      '/api/': [
        {
          text: 'Facade & Services',
          collapsible: true,
          children: [
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
            '/api/role',
            '/api/permission',
            '/api/setting',
            '/api/activity-log',
          ],
        },
        {
          text: 'Traits',
          collapsible: true,
          children: [
            '/api/has-roles',
            '/api/logs-activity',
          ],
        },
        {
          text: 'Middleware',
          collapsible: true,
          children: [
            '/api/check-role',
            '/api/check-permission',
            '/api/set-locale',
          ],
        },
      ],
    },
  }),

  plugins: [
    searchPlugin({
      locales: {
        '/': {
          placeholder: 'Search...',
        },
      },
    }),
    prismjsPlugin({
      themes: {
        light: 'one-light',
        dark: 'one-dark',
      },
    }),
  ],
})
