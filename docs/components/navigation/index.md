# Navigation

Components for navigation and wayfinding.

## Components

| Component | Description |
|-----------|-------------|
| [NavLink](./nav-link.md) | Navigation link with active state |
| [NavGroup](./nav-group.md) | Collapsible navigation group |
| [StepWizard](./step-wizard.md) | Multi-step progress indicator |
| [BackButton](./back-button.md) | Go back navigation |
| [CommandPalette](./command-palette.md) | Keyboard-driven command menu |

## Usage

### NavLink

```vue
<nav class="flex gap-2">
  <NavLink href="/dashboard" :icon="HomeIcon">
    Dashboard
  </NavLink>
  <NavLink href="/users" :icon="UsersIcon">
    Users
  </NavLink>
  <NavLink href="/settings" :icon="CogIcon">
    Settings
  </NavLink>
</nav>
```

### NavGroup

```vue
<NavGroup title="Settings" :icon="CogIcon">
  <NavLink href="/settings/general">General</NavLink>
  <NavLink href="/settings/security">Security</NavLink>
  <NavLink href="/settings/notifications">Notifications</NavLink>
</NavGroup>
```

### StepWizard

```vue
<StepWizard :steps="steps" :current="currentStep">
  <template #step-1>
    Step 1 content
  </template>
  <template #step-2>
    Step 2 content
  </template>
  <template #step-3>
    Step 3 content
  </template>
</StepWizard>
```

### Command Palette

```vue
<CommandPalette
  :commands="commands"
  @select="handleCommand"
/>
```
