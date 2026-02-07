# ErrorState

A component for displaying error messages with a retry action, commonly used when data fetching or operations fail.

## Import

```js
import { ErrorState } from '@cambosoftware/cambo-admin'
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `String` | `'An error occurred'` | Error title text |
| `description` | `String` | `'Please try again or contact support if the problem persists.'` | Error description |
| `retryLabel` | `String` | `'Retry'` | Retry button label |
| `showRetry` | `Boolean` | `true` | Show retry button |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `retry` | - | Emitted when retry button is clicked |

## Slots

| Slot | Description |
|------|-------------|
| `action` | Custom action content (replaces default retry button) |

## Basic Example

```vue
<template>
  <ErrorState @retry="fetchData" />
</template>

<script setup>
import { ErrorState } from '@cambosoftware/cambo-admin'

const fetchData = () => {
  // Retry the failed operation
  console.log('Retrying...')
}
</script>
```

## Custom Title and Description

```vue
<template>
  <ErrorState
    title="Failed to load users"
    description="We couldn't retrieve the user list. Please check your connection and try again."
    @retry="loadUsers"
  />
</template>
```

## Custom Retry Label

```vue
<template>
  <ErrorState
    title="Connection Error"
    description="Unable to connect to the server."
    retry-label="Try Again"
    @retry="reconnect"
  />
</template>
```

## Without Retry Button

```vue
<template>
  <ErrorState
    title="Access Denied"
    description="You don't have permission to view this resource."
    :show-retry="false"
  />
</template>
```

## With Custom Actions

```vue
<template>
  <ErrorState
    title="Page Not Found"
    description="The page you're looking for doesn't exist."
  >
    <template #action>
      <div class="flex gap-2 justify-center">
        <Button variant="secondary" @click="goBack">
          Go Back
        </Button>
        <Button @click="goHome">
          Go to Home
        </Button>
      </div>
    </template>
  </ErrorState>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => router.back()
const goHome = () => router.push('/')
</script>
```

## Data Fetching Error

```vue
<template>
  <div>
    <div v-if="isLoading" class="py-12 text-center">
      <Spinner />
      <p class="mt-2 text-gray-500">Loading...</p>
    </div>

    <ErrorState
      v-else-if="error"
      :title="error.title"
      :description="error.message"
      @retry="fetchData"
    />

    <div v-else>
      <!-- Display data -->
      <List :items="data" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isLoading = ref(true)
const error = ref(null)
const data = ref([])

const fetchData = async () => {
  isLoading.value = true
  error.value = null

  try {
    const response = await fetch('/api/data')
    if (!response.ok) throw new Error('Failed to fetch')
    data.value = await response.json()
  } catch (e) {
    error.value = {
      title: 'Failed to load data',
      message: e.message || 'An unexpected error occurred.'
    }
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchData)
</script>
```

## In a Card

```vue
<template>
  <Card title="User Profile">
    <ErrorState
      v-if="loadError"
      title="Failed to load profile"
      description="We couldn't retrieve the user profile."
      @retry="loadProfile"
    />
    <div v-else>
      <!-- Profile content -->
    </div>
  </Card>
</template>
```

## API Error with Details

```vue
<template>
  <ErrorState
    :title="`Error ${errorCode}`"
    :description="errorMessage"
    @retry="retry"
  >
    <template #action>
      <div class="text-center">
        <Button @click="retry" class="mb-2">
          <ArrowPathIcon class="h-4 w-4 mr-2" />
          Retry
        </Button>
        <p class="text-xs text-gray-400 mt-2">
          Error ID: {{ errorId }}
        </p>
      </div>
    </template>
  </ErrorState>
</template>

<script setup>
const errorCode = 500
const errorMessage = 'Internal server error. Our team has been notified.'
const errorId = 'ERR-12345'
</script>
```

## Form Submission Error

```vue
<template>
  <Form @submit="handleSubmit">
    <FormGroup label="Email">
      <Input v-model="email" type="email" />
    </FormGroup>

    <ErrorState
      v-if="submitError"
      title="Submission Failed"
      :description="submitError"
      @retry="handleSubmit"
      class="my-4"
    />

    <Button type="submit" :loading="isSubmitting">
      Submit
    </Button>
  </Form>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const isSubmitting = ref(false)
const submitError = ref(null)

const handleSubmit = async () => {
  isSubmitting.value = true
  submitError.value = null

  try {
    await submitForm({ email: email.value })
  } catch (e) {
    submitError.value = e.message
  } finally {
    isSubmitting.value = false
  }
}
</script>
```

## Network Error

```vue
<template>
  <ErrorState
    title="No Internet Connection"
    description="Please check your network connection and try again."
    retry-label="Reconnect"
    @retry="checkConnection"
  />
</template>

<script setup>
const checkConnection = () => {
  if (navigator.onLine) {
    location.reload()
  }
}
</script>
```

## Full Page Error

```vue
<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full">
      <ErrorState
        title="Something went wrong"
        description="We're experiencing technical difficulties. Please try again later."
        @retry="() => location.reload()"
      />
    </div>
  </div>
</template>
```

## Timeout Error

```vue
<template>
  <ErrorState
    title="Request Timeout"
    description="The request took too long to complete. Please try again."
    retry-label="Try Again"
    @retry="retryWithTimeout"
  />
</template>

<script setup>
const retryWithTimeout = async () => {
  const controller = new AbortController()
  const timeout = setTimeout(() => controller.abort(), 30000)

  try {
    await fetch('/api/slow-endpoint', { signal: controller.signal })
    clearTimeout(timeout)
  } catch (e) {
    if (e.name === 'AbortError') {
      console.log('Request timed out')
    }
  }
}
</script>
```

## Playground

Try the ErrorState component:

<LiveDemo>
  <div style="padding: 40px; text-align: center; background: #fef2f2; border-radius: 12px; border: 1px solid #fecaca;">
    <div style="width: 48px; height: 48px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
      <svg width="24" height="24" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
    </div>
    <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #991b1b;">Failed to load data</h3>
    <p style="margin: 0 0 20px 0; font-size: 14px; color: #b91c1c;">We couldn't retrieve the data. Please check your connection and try again.</p>
    <button style="padding: 10px 20px; border: none; border-radius: 6px; background: #ef4444; color: white; cursor: pointer; font-size: 14px; font-weight: 500;">
      Retry
    </button>
  </div>

  <template #code>

```vue
<template>
  <ErrorState
    title="Failed to load data"
    description="We couldn't retrieve the data. Please check your connection and try again."
    retry-label="Retry"
    @retry="fetchData"
  />
</template>

<script setup>
const fetchData = () => {
  console.log('Retrying...')
}
</script>
```

  </template>
</LiveDemo>
