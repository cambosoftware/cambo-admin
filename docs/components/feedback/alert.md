# Alert

A component for displaying contextual feedback messages with different severity levels, optional icons, and dismiss functionality.

## Import

```js
import { Alert } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `String` | `'info'` | Alert type: `'info'`, `'success'`, `'warning'`, `'danger'` |
| `title` | `String` | `null` | Optional title text |
| `dismissible` | `Boolean` | `false` | Show dismiss/close button |
| `icon` | `Boolean` | `true` | Show variant-specific icon |
| `border` | `String` | `'none'` | Border style: `'none'`, `'left'`, `'top'` |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `dismiss` | - | Emitted when dismiss button is clicked |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Alert content/message |

## Basic Example

```vue
<template>
  <Alert variant="info">
    This is an informational message.
  </Alert>
</template>

<script setup>
import { Alert } from '@cambosoftware/cambo-admin'
</script>
```

## Variants

```vue
<template>
  <div class="space-y-4">
    <Alert variant="info">
      <strong>Info:</strong> This is an informational alert.
    </Alert>

    <Alert variant="success">
      <strong>Success:</strong> Your changes have been saved successfully.
    </Alert>

    <Alert variant="warning">
      <strong>Warning:</strong> Please review your input before submitting.
    </Alert>

    <Alert variant="danger">
      <strong>Error:</strong> There was a problem processing your request.
    </Alert>
  </div>
</template>
```

## With Title

```vue
<template>
  <Alert variant="success" title="Payment Successful">
    Your payment of $99.00 has been processed. A receipt has been sent to your email.
  </Alert>
</template>
```

## Dismissible Alert

```vue
<template>
  <Alert
    v-if="showAlert"
    variant="warning"
    dismissible
    @dismiss="showAlert = false"
  >
    Your subscription will expire in 7 days. Please renew to continue access.
  </Alert>
</template>

<script setup>
import { ref } from 'vue'

const showAlert = ref(true)
</script>
```

## Border Styles

```vue
<template>
  <div class="space-y-4">
    <!-- Left border -->
    <Alert variant="info" border="left">
      Alert with left border accent.
    </Alert>

    <!-- Top border -->
    <Alert variant="success" border="top">
      Alert with top border accent.
    </Alert>

    <!-- No border -->
    <Alert variant="warning" border="none">
      Alert without border accent.
    </Alert>
  </div>
</template>
```

## Without Icon

```vue
<template>
  <Alert variant="info" :icon="false">
    This alert does not display an icon.
  </Alert>
</template>
```

## With Rich Content

```vue
<template>
  <Alert variant="danger" title="Form Validation Failed">
    <p class="mb-2">Please correct the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
      <li>Email address is invalid</li>
      <li>Password must be at least 8 characters</li>
      <li>Phone number is required</li>
    </ul>
  </Alert>
</template>
```

## With Actions

```vue
<template>
  <Alert variant="warning" title="Unsaved Changes" dismissible @dismiss="handleDismiss">
    <p>You have unsaved changes that will be lost.</p>
    <div class="mt-3 flex gap-2">
      <Button size="sm" variant="warning" @click="saveChanges">
        Save Changes
      </Button>
      <Button size="sm" variant="ghost" @click="discardChanges">
        Discard
      </Button>
    </div>
  </Alert>
</template>
```

## Cookie Banner Example

```vue
<template>
  <Alert
    v-if="showCookieBanner"
    variant="info"
    border="none"
    dismissible
    @dismiss="acceptCookies"
  >
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
      <p class="flex-1">
        We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.
      </p>
      <div class="flex gap-2">
        <Button size="sm" @click="acceptCookies">Accept</Button>
        <Button size="sm" variant="ghost" @click="showSettings">Settings</Button>
      </div>
    </div>
  </Alert>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const showCookieBanner = ref(false)

onMounted(() => {
  showCookieBanner.value = !localStorage.getItem('cookiesAccepted')
})

const acceptCookies = () => {
  localStorage.setItem('cookiesAccepted', 'true')
  showCookieBanner.value = false
}
</script>
```

## Notification List Example

```vue
<template>
  <div class="space-y-3">
    <Alert
      v-for="notification in notifications"
      :key="notification.id"
      :variant="notification.type"
      :title="notification.title"
      dismissible
      @dismiss="removeNotification(notification.id)"
    >
      {{ notification.message }}
    </Alert>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const notifications = ref([
  { id: 1, type: 'success', title: 'Upload Complete', message: 'Your file has been uploaded successfully.' },
  { id: 2, type: 'warning', title: 'Low Storage', message: 'You are running low on storage space.' },
  { id: 3, type: 'info', title: 'New Feature', message: 'Check out our new dashboard features!' },
])

const removeNotification = (id) => {
  notifications.value = notifications.value.filter(n => n.id !== id)
}
</script>
```

## Inline Form Validation

```vue
<template>
  <Form @submit="handleSubmit">
    <FormGroup label="Email">
      <Input v-model="email" type="email" :error="errors.email" />
    </FormGroup>

    <Alert v-if="errors.email" variant="danger" :icon="true" class="mt-2">
      {{ errors.email }}
    </Alert>

    <Button type="submit" class="mt-4">Submit</Button>
  </Form>
</template>
```

## Contextual Colors

| Variant | Background | Text | Border | Use Case |
|---------|------------|------|--------|----------|
| `info` | Sky-50 | Sky-800 | Sky-500 | General information |
| `success` | Emerald-50 | Emerald-800 | Emerald-500 | Successful operations |
| `warning` | Amber-50 | Amber-800 | Amber-500 | Warnings, attention needed |
| `danger` | Red-50 | Red-800 | Red-500 | Errors, critical issues |

## Playground

Try the alert component with different variants:

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoAlert variant="info">This is an informational message.</DemoAlert>
    <DemoAlert variant="success">Your changes have been saved successfully!</DemoAlert>
    <DemoAlert variant="warning">Please review your input before submitting.</DemoAlert>
    <DemoAlert variant="danger">There was an error processing your request.</DemoAlert>
  </div>

  <template #code>

```vue
<Alert variant="info">This is an informational message.</Alert>
<Alert variant="success">Your changes have been saved successfully!</Alert>
<Alert variant="warning">Please review your input before submitting.</Alert>
<Alert variant="danger">There was an error processing your request.</Alert>
```

  </template>
</LiveDemo>

### With Title

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoAlert variant="success" title="Success!">Your payment has been processed.</DemoAlert>
    <DemoAlert variant="danger" title="Error!">Unable to connect to server.</DemoAlert>
  </div>

  <template #code>

```vue
<Alert variant="success" title="Success!">
  Your payment has been processed.
</Alert>
<Alert variant="danger" title="Error!">
  Unable to connect to server.
</Alert>
```

  </template>
</LiveDemo>

### Dismissible Alerts

<LiveDemo>
  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
    <DemoAlert variant="info" dismissible>This alert can be dismissed.</DemoAlert>
    <DemoAlert variant="warning" dismissible title="Attention">Click the X to dismiss this alert.</DemoAlert>
  </div>

  <template #code>

```vue
<Alert variant="info" dismissible @dismiss="handleDismiss">
  This alert can be dismissed.
</Alert>
<Alert variant="warning" dismissible title="Attention" @dismiss="handleDismiss">
  Click the X to dismiss this alert.
</Alert>
```

  </template>
</LiveDemo>
