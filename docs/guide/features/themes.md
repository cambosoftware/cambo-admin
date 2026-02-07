# Themes & Dark Mode

CamboAdmin includes a powerful theming system with dark mode support.

## Overview

The themes module provides:

- Light/Dark/System mode
- Custom color schemes
- CSS variable theming
- User preference persistence
- Smooth transitions

## Configuration

```php
// config/cambo-admin.php
'modules' => [
    'themes' => true,
],

'appearance' => [
    'dark_mode' => 'auto', // 'light', 'dark', 'auto'
    'primary_color' => '#6366f1',
],
```

## Dark Mode Options

| Value | Behavior |
|-------|----------|
| `light` | Always light mode |
| `dark` | Always dark mode |
| `auto` | Follow system preference |

## ThemeService

```php
use CamboSoftware\CamboAdmin\Services\ThemeService;

class ThemeController extends Controller
{
    public function __construct(
        protected ThemeService $themes
    ) {}

    public function update(Request $request)
    {
        $this->themes->setTheme($request->theme);
        $this->themes->setPrimaryColor($request->primary_color);

        return back()->with('success', 'Theme updated.');
    }
}
```

### Available Methods

```php
// Get current theme
$theme = $this->themes->getTheme(); // 'light', 'dark', or 'auto'

// Set theme
$this->themes->setTheme('dark');

// Get primary color
$color = $this->themes->getPrimaryColor();

// Set primary color
$this->themes->setPrimaryColor('#3b82f6');

// Get all theme settings
$settings = $this->themes->getSettings();
```

## Vue Theme Toggle

```vue
<template>
  <Dropdown align="right">
    <template #trigger>
      <IconButton :icon="themeIcon" variant="ghost" />
    </template>

    <DropdownItem @click="setTheme('light')">
      <SunIcon class="w-4 h-4 mr-2" />
      Light
    </DropdownItem>
    <DropdownItem @click="setTheme('dark')">
      <MoonIcon class="w-4 h-4 mr-2" />
      Dark
    </DropdownItem>
    <DropdownItem @click="setTheme('auto')">
      <ComputerDesktopIcon class="w-4 h-4 mr-2" />
      System
    </DropdownItem>
  </Dropdown>
</template>

<script setup>
import { computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { SunIcon, MoonIcon, ComputerDesktopIcon } from '@heroicons/vue/24/outline'

const theme = usePage().props.theme

const themeIcon = computed(() => {
  switch(theme) {
    case 'dark': return MoonIcon
    case 'light': return SunIcon
    default: return ComputerDesktopIcon
  }
})

const setTheme = (newTheme) => {
  router.post(route('theme.update'), { theme: newTheme })
}
</script>
```

## useTheme Composable

```vue
<script setup>
import { useTheme } from '@/Composables/useTheme'

const { theme, isDark, setTheme, toggleTheme } = useTheme()
</script>

<template>
  <Button @click="toggleTheme">
    {{ isDark ? 'Light Mode' : 'Dark Mode' }}
  </Button>
</template>
```

## CSS Variables

CamboAdmin uses CSS variables for theming:

```css
:root {
  /* Primary colors */
  --color-primary-50: 238 242 255;
  --color-primary-100: 224 231 255;
  --color-primary-500: 99 102 241;
  --color-primary-600: 79 70 229;
  --color-primary-700: 67 56 202;

  /* Background */
  --color-bg: 255 255 255;
  --color-bg-secondary: 249 250 251;

  /* Text */
  --color-text: 17 24 39;
  --color-text-secondary: 107 114 128;

  /* Border */
  --color-border: 229 231 235;
}

.dark {
  --color-bg: 17 24 39;
  --color-bg-secondary: 31 41 55;
  --color-text: 249 250 251;
  --color-text-secondary: 156 163 175;
  --color-border: 55 65 81;
}
```

## Custom Color Schemes

### Define a Custom Theme

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          50: 'rgb(var(--color-primary-50) / <alpha-value>)',
          100: 'rgb(var(--color-primary-100) / <alpha-value>)',
          500: 'rgb(var(--color-primary-500) / <alpha-value>)',
          600: 'rgb(var(--color-primary-600) / <alpha-value>)',
          700: 'rgb(var(--color-primary-700) / <alpha-value>)',
        }
      }
    }
  }
}
```

### Apply Custom Colors

```vue
<template>
  <div class="theme-settings">
    <h3>Primary Color</h3>
    <div class="flex gap-2">
      <button
        v-for="color in colors"
        :key="color.value"
        :style="{ backgroundColor: color.value }"
        class="w-8 h-8 rounded-full"
        @click="setPrimaryColor(color.value)"
      />
    </div>
  </div>
</template>

<script setup>
const colors = [
  { name: 'Indigo', value: '#6366f1' },
  { name: 'Blue', value: '#3b82f6' },
  { name: 'Green', value: '#22c55e' },
  { name: 'Purple', value: '#a855f7' },
  { name: 'Pink', value: '#ec4899' },
  { name: 'Orange', value: '#f97316' },
]

const setPrimaryColor = (color) => {
  document.documentElement.style.setProperty('--color-primary-500', hexToRgb(color))
  router.post(route('theme.color'), { color })
}
</script>
```

## Sidebar Theme

```php
// config/cambo-admin.php
'sidebar' => [
    'theme' => 'dark', // 'dark', 'light'
],
```

```vue
<!-- Components adapt to sidebar theme -->
<Sidebar :theme="sidebarTheme">
  <NavLink>Dashboard</NavLink>
  <NavLink>Users</NavLink>
</Sidebar>
```

## Persisting Theme Preference

### Session Storage (Guest)

```javascript
localStorage.setItem('theme', 'dark')
```

### Database (Authenticated)

```php
// Add to users table migration
$table->string('theme')->default('auto');
$table->string('primary_color')->nullable();

// User model
protected $fillable = ['theme', 'primary_color'];
```

## Dark Mode Classes

All CamboAdmin components support dark mode:

```html
<!-- Automatic dark mode support -->
<div class="bg-white dark:bg-gray-800">
  <p class="text-gray-900 dark:text-white">Content</p>
</div>

<!-- Components automatically adapt -->
<Card> <!-- White in light, gray-800 in dark -->
  <Button variant="primary">Action</Button>
</Card>
```

## Transition Effects

Smooth theme transitions:

```css
/* Add to your CSS */
* {
  transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}
```
