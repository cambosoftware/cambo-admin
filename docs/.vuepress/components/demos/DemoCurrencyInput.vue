<template>
  <div class="demo-currency-showcase">
    <div class="demo-currency-item">
      <span class="demo-label">Price (USD):</span>
      <div class="demo-currency-input">
        <span class="demo-currency-input__symbol">$</span>
        <input
          type="text"
          class="demo-currency-input__input"
          :value="displayValueUSD"
          placeholder="0.00"
          @input="handleInputUSD($event.target.value)"
          @blur="formatValueUSD"
        />
        <span class="demo-currency-input__currency">USD</span>
      </div>
      <span class="demo-hint">Value: ${{ priceUSD.toFixed(2) }}</span>
    </div>

    <div class="demo-currency-item">
      <span class="demo-label">Amount (EUR):</span>
      <div class="demo-currency-input demo-currency-input--euro">
        <span class="demo-currency-input__symbol">EUR</span>
        <input
          type="text"
          class="demo-currency-input__input"
          :value="displayValueEUR"
          placeholder="0,00"
          @input="handleInputEUR($event.target.value)"
          @blur="formatValueEUR"
        />
        <span class="demo-currency-input__currency">EUR</span>
      </div>
      <span class="demo-hint">Value: EUR {{ priceEUR.toFixed(2) }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const priceUSD = ref(1234.56)
const priceEUR = ref(987.65)
const displayValueUSD = ref('')
const displayValueEUR = ref('')

const formatNumber = (value, locale = 'en-US') => {
  if (value === '' || value === null || value === undefined) return ''
  const num = parseFloat(value)
  if (isNaN(num)) return ''
  return num.toLocaleString(locale, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

const handleInputUSD = (value) => {
  const cleaned = value.replace(/[^\d.]/g, '')
  const parts = cleaned.split('.')
  let formatted = parts[0]
  if (parts.length > 1) {
    formatted += '.' + parts[1].slice(0, 2)
  }
  displayValueUSD.value = formatted
  priceUSD.value = parseFloat(formatted) || 0
}

const formatValueUSD = () => {
  displayValueUSD.value = formatNumber(priceUSD.value, 'en-US')
}

const handleInputEUR = (value) => {
  const cleaned = value.replace(/[^\d,]/g, '').replace(',', '.')
  const parts = cleaned.split('.')
  let formatted = parts[0]
  if (parts.length > 1) {
    formatted += '.' + parts[1].slice(0, 2)
  }
  displayValueEUR.value = formatted.replace('.', ',')
  priceEUR.value = parseFloat(formatted) || 0
}

const formatValueEUR = () => {
  displayValueEUR.value = formatNumber(priceEUR.value, 'de-DE')
}

onMounted(() => {
  formatValueUSD()
  formatValueEUR()
})
</script>

<style scoped>
.demo-currency-showcase { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }
.demo-currency-item { display: flex; flex-direction: column; gap: 0.5rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-hint { font-size: 0.75rem; color: #6b7280; }

.demo-currency-input {
  display: flex;
  align-items: center;
  padding: 0 0.75rem;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  transition: border-color 0.15s, box-shadow 0.15s;
  width: 100%;
  max-width: 14rem;
}

.demo-currency-input:focus-within {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-currency-input__symbol {
  font-size: 0.875rem;
  color: #6b7280;
  margin-right: 0.25rem;
}

.demo-currency-input__input {
  flex: 1;
  padding: 0.5rem 0;
  font-size: 0.875rem;
  text-align: right;
  border: none;
  outline: none;
  background: transparent;
}

.demo-currency-input__currency {
  font-size: 0.75rem;
  color: #6b7280;
  margin-left: 0.5rem;
  padding: 0.125rem 0.375rem;
  background: #f3f4f6;
  border-radius: 0.25rem;
}

.demo-currency-input--euro .demo-currency-input__symbol {
  font-size: 0.75rem;
  padding: 0.125rem 0.375rem;
  background: #f3f4f6;
  border-radius: 0.25rem;
  margin-right: 0.5rem;
}
</style>
