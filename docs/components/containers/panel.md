# Panel

A structured container component with a header, optional collapsible content, and footer. Similar to Card but with built-in collapse functionality.

## Import

```vue
<script setup>
import { Panel } from '@cambosoftware/cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | **required** | Panel header title |
| `subtitle` | `String` | `null` | Optional subtitle below the title |
| `collapsible` | `Boolean` | `false` | Enable collapse functionality |
| `defaultOpen` | `Boolean` | `true` | Initial state when collapsible (open by default) |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Main panel content |
| `header-actions` | Actions in the header (right side of title) |
| `footer` | Footer content with gray background |

## Events

This component does not emit any events. The collapse state is managed internally.

## Examples

### Basic Panel

```vue
<template>
    <Panel title="User Information">
        <p>Panel content goes here.</p>
    </Panel>
</template>
```

### Panel with Subtitle

```vue
<template>
    <Panel
        title="Account Settings"
        subtitle="Manage your account preferences"
    >
        <form class="space-y-4">
            <Input label="Display Name" v-model="displayName" />
            <Input label="Email" v-model="email" type="email" />
        </form>
    </Panel>
</template>
```

### Collapsible Panel

```vue
<template>
    <Panel
        title="Advanced Options"
        collapsible
    >
        <div class="space-y-4">
            <Toggle v-model="debugMode" label="Enable debug mode" />
            <Toggle v-model="verboseLogging" label="Verbose logging" />
        </div>
    </Panel>
</template>
```

### Collapsible Panel (Closed by Default)

```vue
<template>
    <Panel
        title="Developer Settings"
        collapsible
        :defaultOpen="false"
    >
        <p>These settings are hidden by default.</p>
    </Panel>
</template>
```

### Panel with Header Actions

```vue
<template>
    <Panel title="Team Members">
        <template #header-actions>
            <Button size="sm" variant="outline">
                <PlusIcon class="h-4 w-4 mr-1" />
                Add Member
            </Button>
        </template>

        <ul class="divide-y divide-gray-200">
            <li v-for="member in members" :key="member.id" class="py-3">
                {{ member.name }}
            </li>
        </ul>
    </Panel>
</template>
```

### Panel with Footer

```vue
<template>
    <Panel title="Edit Profile">
        <form class="space-y-4">
            <Input label="First Name" v-model="form.firstName" />
            <Input label="Last Name" v-model="form.lastName" />
            <Textarea label="Bio" v-model="form.bio" />
        </form>

        <template #footer>
            <div class="flex justify-end gap-2">
                <Button variant="outline" @click="cancel">Cancel</Button>
                <Button @click="save">Save Changes</Button>
            </div>
        </template>
    </Panel>
</template>
```

### Collapsible with Header Actions

```vue
<template>
    <Panel
        title="Notifications"
        subtitle="Configure your notification preferences"
        collapsible
    >
        <template #header-actions>
            <Badge variant="info">3 new</Badge>
        </template>

        <div class="space-y-3">
            <Toggle v-model="notifications.email" label="Email notifications" />
            <Toggle v-model="notifications.push" label="Push notifications" />
            <Toggle v-model="notifications.sms" label="SMS notifications" />
        </div>
    </Panel>
</template>
```

### Settings Page with Multiple Panels

```vue
<template>
    <div class="space-y-6">
        <Panel title="Profile" subtitle="Your public profile information">
            <div class="space-y-4">
                <Input label="Username" v-model="profile.username" />
                <Textarea label="About" v-model="profile.about" />
            </div>
            <template #footer>
                <Button>Update Profile</Button>
            </template>
        </Panel>

        <Panel title="Security" subtitle="Manage your security settings" collapsible>
            <div class="space-y-4">
                <PasswordInput label="Current Password" v-model="security.current" />
                <PasswordInput label="New Password" v-model="security.new" />
            </div>
            <template #footer>
                <Button>Change Password</Button>
            </template>
        </Panel>

        <Panel title="Danger Zone" collapsible :defaultOpen="false">
            <Alert variant="danger">
                <p>These actions are irreversible. Please be certain.</p>
            </Alert>
            <template #footer>
                <Button variant="danger">Delete Account</Button>
            </template>
        </Panel>
    </div>
</template>
```

### Dashboard Widget Panel

```vue
<template>
    <Panel title="Recent Activity" collapsible>
        <template #header-actions>
            <button class="text-sm text-primary-600 hover:text-primary-800">
                View all
            </button>
        </template>

        <Timeline :items="recentActivity" />
    </Panel>
</template>
```

### Form Section Panels

```vue
<template>
    <form @submit.prevent="submitForm" class="space-y-6">
        <Panel title="Personal Information">
            <div class="grid grid-cols-2 gap-4">
                <Input label="First Name" v-model="form.firstName" />
                <Input label="Last Name" v-model="form.lastName" />
                <Input label="Email" v-model="form.email" type="email" class="col-span-2" />
            </div>
        </Panel>

        <Panel title="Address">
            <div class="space-y-4">
                <Input label="Street Address" v-model="form.address" />
                <div class="grid grid-cols-3 gap-4">
                    <Input label="City" v-model="form.city" />
                    <Input label="State" v-model="form.state" />
                    <Input label="ZIP" v-model="form.zip" />
                </div>
            </div>
        </Panel>

        <div class="flex justify-end">
            <Button type="submit">Submit</Button>
        </div>
    </form>
</template>
```

## Styling

The Panel component uses:

- Container: `bg-white rounded-xl ring-1 ring-gray-200 overflow-hidden`
- Header: `px-5 py-3 border-b border-gray-200`
- Header (collapsible): `hover:bg-gray-50 cursor-pointer`
- Title: `text-sm font-semibold text-gray-900`
- Subtitle: `text-xs text-gray-500`
- Content: `px-5 py-4`
- Footer: `px-5 py-3 border-t border-gray-200 bg-gray-50`

## Differences from Card

| Feature | Panel | Card |
|---------|-------|------|
| Title | Required | Optional |
| Built-in collapse | Yes | No |
| Hover effect | No | Optional |
| Border toggle | No | Yes |
| Overflow control | No | Yes |

## When to Use

- Use **Panel** for form sections, settings groups, or collapsible content areas
- Use **Card** for general-purpose content containers with flexible styling options

## Playground

Try the Panel component:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 16px;">
    <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
      <div style="padding: 12px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h3 style="margin: 0; font-size: 14px; font-weight: 600; color: #111827;">Team Members</h3>
          <p style="margin: 2px 0 0 0; font-size: 12px; color: #6b7280;">Manage your team</p>
        </div>
        <button style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer; font-size: 13px;">Add Member</button>
      </div>
      <div style="padding: 16px 20px;">
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; background: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">JD</div>
            <span style="font-size: 14px; color: #374151;">John Doe</span>
          </div>
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">JS</div>
            <span style="font-size: 14px; color: #374151;">Jane Smith</span>
          </div>
        </div>
      </div>
      <div style="padding: 12px 20px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
        <span style="font-size: 13px; color: #6b7280;">2 members total</span>
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <Panel title="Team Members" subtitle="Manage your team">
    <template #header-actions>
      <Button size="sm" variant="outline">Add Member</Button>
    </template>

    <ul class="divide-y divide-gray-200">
      <li v-for="member in members" :key="member.id" class="py-3">
        {{ member.name }}
      </li>
    </ul>

    <template #footer>
      <span class="text-sm text-gray-500">2 members total</span>
    </template>
  </Panel>
</template>
```

  </template>
</LiveDemo>
