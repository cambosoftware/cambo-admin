# PhoneInput

An international phone number input with country selector, flags, and dial code support.

## Import

```vue
<script setup>
import { PhoneInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `String` | `''` | The full phone number including dial code (v-model) |
| `defaultCountry` | `String` | `'FR'` | Default country code (ISO 2-letter code) |
| `placeholder` | `String` | `null` | Custom placeholder (defaults to country-specific format) |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the input |
| `error` | `String \| Boolean` | `null` | Error state or error message |

## Supported Countries

The component includes 27 countries with their dial codes and flags:

| Country | Code | Dial | Flag |
|---------|------|------|------|
| France | FR | +33 | FR |
| Belgium | BE | +32 | BE |
| Switzerland | CH | +41 | CH |
| Canada | CA | +1 | CA |
| United States | US | +1 | US |
| United Kingdom | GB | +44 | GB |
| Germany | DE | +49 | DE |
| Spain | ES | +34 | ES |
| Italy | IT | +39 | IT |
| Portugal | PT | +351 | PT |
| Netherlands | NL | +31 | NL |
| Luxembourg | LU | +352 | LU |
| Cambodia | KH | +855 | KH |
| Thailand | TH | +66 | TH |
| Vietnam | VN | +84 | VN |
| Japan | JP | +81 | JP |
| China | CN | +86 | CN |
| India | IN | +91 | IN |
| Morocco | MA | +212 | MA |
| Tunisia | TN | +216 | TN |
| Algeria | DZ | +213 | DZ |
| Senegal | SN | +221 | SN |
| Ivory Coast | CI | +225 | CI |
| Cameroon | CM | +237 | CM |
| Australia | AU | +61 | AU |
| Brazil | BR | +55 | BR |
| Mexico | MX | +52 | MX |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `String` | Emitted when phone changes (for v-model) |
| `change` | `String` | Emitted when phone changes |
| `countryChange` | `Object` | Emitted when country is changed |
| `focus` | `FocusEvent` | Emitted when input gains focus |
| `blur` | `FocusEvent` | Emitted when input loses focus |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <PhoneInput v-model="phone" />
</template>

<script setup>
import { ref } from 'vue'
const phone = ref('')
</script>
```

### With Default Country

```vue
<template>
  <PhoneInput v-model="phone" default-country="US" />
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <PhoneInput v-model="phone" size="sm" />
    <PhoneInput v-model="phone" size="md" />
    <PhoneInput v-model="phone" size="lg" />
  </div>
</template>
```

### With Custom Placeholder

```vue
<template>
  <PhoneInput
    v-model="phone"
    placeholder="Your phone number"
  />
</template>
```

### With Error State

```vue
<template>
  <PhoneInput
    v-model="phone"
    :error="!phone ? 'Phone number is required' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <PhoneInput v-model="phone" disabled />
</template>
```

### In a Registration Form

```vue
<template>
  <form @submit.prevent="register">
    <FormGroup label="Name" required>
      <Input v-model="form.name" :error="errors.name" />
    </FormGroup>

    <FormGroup label="Email" required>
      <Input v-model="form.email" type="email" :error="errors.email" />
    </FormGroup>

    <FormGroup label="Phone Number" required>
      <PhoneInput
        v-model="form.phone"
        :error="errors.phone"
        @country-change="handleCountryChange"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Register
    </button>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  name: '',
  email: '',
  phone: ''
})

const errors = ref({})

const handleCountryChange = (country) => {
  console.log('Country changed:', country)
}
</script>
```

### Contact Form

```vue
<template>
  <form @submit.prevent="submit">
    <FormGroup label="Phone (Primary)" required>
      <PhoneInput
        v-model="contact.primaryPhone"
        default-country="FR"
        :error="errors.primaryPhone"
      />
    </FormGroup>

    <FormGroup label="Phone (Secondary)">
      <PhoneInput
        v-model="contact.secondaryPhone"
        default-country="FR"
      />
    </FormGroup>

    <FormGroup label="WhatsApp">
      <PhoneInput
        v-model="contact.whatsapp"
        default-country="FR"
      />
    </FormGroup>
  </form>
</template>
```

### International User Profile

```vue
<template>
  <FormGroup label="Phone Number">
    <PhoneInput
      v-model="profile.phone"
      :default-country="profile.country"
      @country-change="country => profile.country = country.code"
    />
    <p class="mt-1 text-xs text-gray-500">
      Include country code for international calling
    </p>
  </FormGroup>
</template>
```

### Multi-regional Support

```vue
<template>
  <div class="grid grid-cols-2 gap-4">
    <FormGroup label="Office (Europe)">
      <PhoneInput
        v-model="phones.europe"
        default-country="FR"
      />
    </FormGroup>

    <FormGroup label="Office (North America)">
      <PhoneInput
        v-model="phones.northAmerica"
        default-country="US"
      />
    </FormGroup>

    <FormGroup label="Office (Asia)">
      <PhoneInput
        v-model="phones.asia"
        default-country="JP"
      />
    </FormGroup>

    <FormGroup label="Office (Australia)">
      <PhoneInput
        v-model="phones.australia"
        default-country="AU"
      />
    </FormGroup>
  </div>
</template>
```

### With Phone Validation

```vue
<template>
  <FormGroup label="Phone Number">
    <PhoneInput
      v-model="phone"
      :error="phoneError"
    />
  </FormGroup>
</template>

<script setup>
import { ref, computed } from 'vue'

const phone = ref('')

const phoneError = computed(() => {
  if (!phone.value) return null
  // Simple validation: check if there are enough digits
  const digits = phone.value.replace(/\D/g, '')
  if (digits.length < 10) {
    return 'Please enter a valid phone number'
  }
  return null
})
</script>
```

## Features

- Country selector dropdown with flags
- Searchable country list
- Automatic dial code prefix
- Country-specific placeholder formats
- Accepts only phone number characters (digits, spaces, dashes, parentheses)
- Full phone number output (dial code + local number)
- Flag emoji display
- Click outside to close dropdown
- Three size variants
- Proper focus management

## Playground

Try the PhoneInput component:

<LiveDemo>
  <DemoPhoneInput />

  <template #code>

```vue
<script setup>
import { PhoneInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const phone = ref('')
</script>

<template>
    <PhoneInput
        v-model="phone"
        default-country="US"
    />
</template>
```

  </template>
</LiveDemo>
