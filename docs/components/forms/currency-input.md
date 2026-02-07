# CurrencyInput

A currency input component with locale-aware formatting, symbol display, and precision control.

## Import

```vue
<script setup>
import { CurrencyInput } from 'cambo-admin'
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | `Number \| null` | `null` | The numeric value (v-model) |
| `currency` | `String` | `'EUR'` | Currency code (EUR, USD, GBP, etc.) |
| `currencySymbol` | `String` | `null` | Custom currency symbol (overrides default) |
| `locale` | `String` | `'fr-FR'` | Locale for number formatting |
| `placeholder` | `String` | `'0,00'` | Placeholder text |
| `size` | `String` | `'md'` | Input size: `'sm'`, `'md'`, or `'lg'` |
| `disabled` | `Boolean` | `false` | Disable the input |
| `error` | `String \| Boolean` | `null` | Error state or error message |
| `min` | `Number` | `null` | Minimum value |
| `max` | `Number` | `null` | Maximum value |
| `precision` | `Number` | `2` | Decimal precision |

## Supported Currencies

The component includes built-in symbols for common currencies:

| Code | Symbol |
|------|--------|
| EUR | euro |
| USD | $ |
| GBP | pound |
| JPY | yen |
| CHF | CHF |
| CAD | CA$ |
| KHR | riel |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | `Number \| null` | Emitted when value changes (for v-model) |
| `change` | `Number \| null` | Emitted when value changes (on blur) |
| `focus` | `FocusEvent` | Emitted when input gains focus |
| `blur` | `FocusEvent` | Emitted when input loses focus |

## Exposed Methods

| Method | Description |
|--------|-------------|
| `focus()` | Focus the input |

## Slots

This component does not expose any slots.

## Examples

### Basic Usage

```vue
<template>
  <CurrencyInput v-model="amount" />
</template>

<script setup>
import { ref } from 'vue'
const amount = ref(null)
</script>
```

### With Initial Value

```vue
<template>
  <CurrencyInput v-model="price" />
</template>

<script setup>
import { ref } from 'vue'
const price = ref(99.99)
</script>
```

### Different Currencies

```vue
<template>
  <div class="space-y-4">
    <CurrencyInput v-model="eur" currency="EUR" />
    <CurrencyInput v-model="usd" currency="USD" />
    <CurrencyInput v-model="gbp" currency="GBP" />
    <CurrencyInput v-model="jpy" currency="JPY" :precision="0" />
  </div>
</template>
```

### Custom Currency Symbol

```vue
<template>
  <CurrencyInput
    v-model="amount"
    currency-symbol="BTC"
    :precision="8"
  />
</template>
```

### Different Locales

```vue
<template>
  <div class="space-y-4">
    <!-- French (1 234,56) -->
    <CurrencyInput v-model="amount" locale="fr-FR" />

    <!-- US English (1,234.56) -->
    <CurrencyInput v-model="amount" locale="en-US" currency="USD" />

    <!-- German (1.234,56) -->
    <CurrencyInput v-model="amount" locale="de-DE" />
  </div>
</template>
```

### With Min/Max Constraints

```vue
<template>
  <CurrencyInput
    v-model="amount"
    :min="0"
    :max="10000"
  />
</template>
```

### Custom Precision

```vue
<template>
  <div class="space-y-4">
    <!-- No decimals -->
    <CurrencyInput v-model="whole" :precision="0" />

    <!-- 2 decimals (default) -->
    <CurrencyInput v-model="standard" :precision="2" />

    <!-- 4 decimals -->
    <CurrencyInput v-model="precise" :precision="4" />
  </div>
</template>
```

### Different Sizes

```vue
<template>
  <div class="space-y-4">
    <CurrencyInput v-model="amount" size="sm" />
    <CurrencyInput v-model="amount" size="md" />
    <CurrencyInput v-model="amount" size="lg" />
  </div>
</template>
```

### With Error State

```vue
<template>
  <CurrencyInput
    v-model="amount"
    :error="amount === null ? 'Amount is required' : false"
  />
</template>
```

### Disabled State

```vue
<template>
  <CurrencyInput v-model="amount" disabled />
</template>
```

### In a Form (Price Input)

```vue
<template>
  <form @submit.prevent="saveProduct">
    <FormGroup label="Product Name" required>
      <Input v-model="product.name" :error="errors.name" />
    </FormGroup>

    <FormGroup label="Price" required>
      <CurrencyInput
        v-model="product.price"
        currency="EUR"
        :min="0"
        :error="errors.price"
      />
    </FormGroup>

    <FormGroup label="Sale Price">
      <CurrencyInput
        v-model="product.salePrice"
        currency="EUR"
        :min="0"
        :max="product.price"
      />
    </FormGroup>

    <button type="submit" class="btn btn-primary">
      Save Product
    </button>
  </form>
</template>
```

### Invoice Line Items

```vue
<template>
  <table>
    <thead>
      <tr>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, index) in items" :key="index">
        <td>
          <Input v-model="item.description" />
        </td>
        <td class="w-24">
          <NumberInput v-model="item.quantity" :min="1" />
        </td>
        <td class="w-40">
          <CurrencyInput v-model="item.unitPrice" />
        </td>
        <td class="w-40">
          <CurrencyInput
            :model-value="item.quantity * item.unitPrice"
            disabled
          />
        </td>
      </tr>
    </tbody>
  </table>
</template>
```

### Multi-currency Support

```vue
<template>
  <div class="flex gap-2">
    <Select v-model="currency" :options="currencies" class="w-24" />
    <CurrencyInput
      v-model="amount"
      :currency="currency"
      :locale="localeForCurrency"
      class="flex-1"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const currency = ref('EUR')
const amount = ref(null)
const currencies = ['EUR', 'USD', 'GBP', 'JPY', 'CHF']

const localeForCurrency = computed(() => {
  const locales = {
    EUR: 'fr-FR',
    USD: 'en-US',
    GBP: 'en-GB',
    JPY: 'ja-JP',
    CHF: 'de-CH'
  }
  return locales[currency.value] || 'en-US'
})
</script>
```

### Budget Tracker

```vue
<template>
  <div class="space-y-4">
    <FormGroup label="Budget">
      <CurrencyInput
        v-model="budget"
        :min="0"
      />
    </FormGroup>

    <FormGroup label="Spent">
      <CurrencyInput
        v-model="spent"
        :min="0"
        :max="budget"
        :error="spent > budget ? 'Exceeds budget' : false"
      />
    </FormGroup>

    <div class="flex justify-between text-sm">
      <span>Remaining:</span>
      <span :class="remaining < 0 ? 'text-red-600' : 'text-green-600'">
        {{ formatCurrency(remaining) }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const budget = ref(1000)
const spent = ref(0)

const remaining = computed(() => budget.value - spent.value)

const formatCurrency = (value) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(value)
}
</script>
```

## Features

- Currency symbol prefix
- Locale-aware number formatting
- Automatic formatting on blur
- Raw number display during editing
- Min/max value constraints
- Configurable decimal precision
- Right-aligned text for numeric clarity
- French comma decimal separator (locale-dependent)
- Numeric value binding (not string)
- Focus management
- Three size variants

## Playground

Try the CurrencyInput component:

<LiveDemo>
  <DemoCurrencyInput />

  <template #code>

```vue
<script setup>
import { CurrencyInput } from '@cambosoftware/cambo-admin'
import { ref } from 'vue'

const amount = ref(99.99)
</script>

<template>
    <CurrencyInput
        v-model="amount"
        currency="USD"
        locale="en-US"
        :min="0"
        :max="10000"
    />
</template>
```

  </template>
</LiveDemo>
