# PasswordInput

A password input component with visibility toggle and optional strength meter.

## Import

```vue
<script setup>
import { PasswordInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The password value (v-model) |
| `placeholder` | `String` | `null` | Placeholder text |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the input |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `toggleable` | `Boolean` | `true` | Show visibility toggle button |
| `icon` | `Object \| Function` | `null` | Custom left icon component |
| `strengthMeter` | `Boolean` | `false` | Show password strength indicator |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String` | Emitted when password changes (for v-model) |
| `focus` | `FocusEvent` | Emitted when input gains focus |
| `blur` | `FocusEvent` | Emitted when input loses focus |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `focus()` | Focus the input |

## Slots

This component does not expose any slots.

## Password Strength Criteria

When `strengthMeter` is enabled, the strength is calculated based on:

1. **Length**: Password is at least 8 characters
2. **Mixed case**: Contains both lowercase and uppercase letters
3. **Numbers**: Contains at least one digit
4. **Special characters**: Contains non-alphanumeric characters

Strength levels:
- **1/4 - Weak**: Red indicator
- **2/4 - Fair**: Orange indicator
- **3/4 - Good**: Yellow indicator
- **4/4 - Strong**: Green indicator

## Examples

### Basic Usage

```vue
<template>
  <PasswordInput v-model="password" placeholder="Enter password" />
</template>

<script setup>
import { ref } from 'vue'
const password = ref('')
</script>
```

### Without Visibility Toggle

```vue
<template>
  <PasswordInput
    v-model="password"
    :toggleable="false"
    placeholder="Password (hidden)"
  />
</template>
```

### With Strength Meter

```vue
<template>
  <PasswordInput
    v-model="password"
    strength-meter
    placeholder="Create a strong password"
  />
</template>
```

### With Custom Icon

```vue
<template>
  <PasswordInput
    v-model="password"
    :icon="LockClosedIcon"
    placeholder="Secure password"
  />
</template>

<script setup>
import { ref } from 'vue'
import { LockClosedIcon } from '@heroicons/vue/24/outline'

const password = ref('')
</script>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <PasswordInput v-model="password" size="sm" placeholder="Small" />
    <PasswordInput v-model="password" size="md" placeholder="Medium" />
    <PasswordInput v-model="password" size="lg" placeholder="Large" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <PasswordInput
    v-model="password"
    :error="password.length < 8 ? 'Password must be at least 8 characters' : false"
    placeholder="Enter password"
  />
</template>
```

### Disabled State

```vue
<template>
  <PasswordInput
    v-model="password"
    disabled
    placeholder="Disabled"
  />
</template>
```

### In a Login Form

```vue
<template>
  <form @submit.prevent="login">
    <FormGroup label="Email" required>
      <Input
        v-model="form.email"
        type="email"
        placeholder="email@example.com"
        :error="errors.email"
      />
    </FormGroup>

    <FormGroup label="Password" required>
      <PasswordInput
        v-model="form.password"
        placeholder="Enter your password"
        :error="errors.password"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary w-full">
      Sign In
    </button>
  </form>
</template>
```

### In a Registration Form

```vue
<template>
  <form @submit.prevent="register">
    <FormGroup label="Password" required>
      <PasswordInput
        v-model="form.password"
        strength-meter
        placeholder="Create a password"
        :error="errors.password"
      />
      <p class="mt-1 text-xs text-gray-500">
        Use 8+ characters with mixed case, numbers, and symbols
      </p>
    </FormGroup>

    <FormGroup label="Confirm Password" required>
      <PasswordInput
        v-model="form.passwordConfirm"
        placeholder="Confirm your password"
        :error="errors.passwordConfirm"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary w-full">
      Create Account
    </button>
  </form>
</template>

<script setup>
import { ref, computed } from 'vue'

const form = ref({
  password: '',
  passwordConfirm: ''
})

const errors = computed(() => ({
  password: form.value.password.length < 8 ? 'Password too short' : null,
  passwordConfirm: form.value.passwordConfirm !== form.value.password
    ? 'Passwords do not match'
    : null
}))
</script>
```

### Change Password Form

```vue
<template>
  <form @submit.prevent="changePassword">
    <FormGroup label="Current Password" required>
      <PasswordInput
        v-model="form.currentPassword"
        placeholder="Enter current password"
        :error="errors.currentPassword"
      />
    </FormGroup>

    <FormGroup label="New Password" required>
      <PasswordInput
        v-model="form.newPassword"
        strength-meter
        placeholder="Enter new password"
        :error="errors.newPassword"
      />
    </FormGroup>

    <FormGroup label="Confirm New Password" required>
      <PasswordInput
        v-model="form.confirmPassword"
        placeholder="Confirm new password"
        :error="errors.confirmPassword"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Update Password
    </button>
  </form>
</template>
```

### API Key Input

```vue
<template>
  <FormGroup label="API Key">
    <PasswordInput
      v-model="apiKey"
      :toggleable="true"
      :icon="KeyIcon"
      placeholder="Enter your API key"
    />
    <p class="mt-1 text-xs text-gray-500">
      Keep your API key secret. Never share it publicly.
    </p>
  </FormGroup>
</template>

<script setup>
import { ref } from 'vue'
import { KeyIcon } from '@heroicons/vue/24/outline'

const apiKey = ref('')
</script>
```

## Features

- Password visibility toggle (eye icon)
- Password strength meter with visual indicators
- Strength calculation based on multiple criteria
- Strength label display (Weak/Fair/Good/Strong)
- Custom left icon support
- Focus management
- Three size variants
- Error state styling
- Maintains focus when toggling visibility
- Accessible toggle button with proper tabindex

## Playground

Try the PasswordInput component:

<LiveDemo>
  <DemoPasswordInput />

  <template #code>

```vue
<script setup>
import { PasswordInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const password = ref('')
</script>

<template>
    <PasswordInput
        v-model="password"
        strength-meter
        placeholder="Create a strong password"
    />
</template>
```

  </template>
</LiveDemo>
