<template>
  <div class="demo-phone-showcase">
    <span class="demo-label">Phone number:</span>
    <div class="demo-phone-input">
      <button class="demo-phone-input__country" @click="showDropdown = !showDropdown">
        <span class="demo-phone-input__flag">{{ selectedCountry.flag }}</span>
        <span class="demo-phone-input__code">{{ selectedCountry.code }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </button>
      <input
        type="tel"
        class="demo-phone-input__input"
        v-model="phone"
        placeholder="(555) 123-4567"
        @input="formatPhone"
      />
      <div v-if="showDropdown" class="demo-phone-input__dropdown">
        <input
          v-model="search"
          type="text"
          class="demo-phone-input__search"
          placeholder="Search country..."
        />
        <div class="demo-phone-input__options">
          <button
            v-for="country in filteredCountries"
            :key="country.code"
            class="demo-phone-input__option"
            @click="selectCountry(country)"
          >
            <span class="demo-phone-input__flag">{{ country.flag }}</span>
            <span>{{ country.name }}</span>
            <span class="demo-phone-input__option-code">{{ country.code }}</span>
          </button>
        </div>
      </div>
    </div>
    <span class="demo-selected">Full number: {{ selectedCountry.code }} {{ phone || '(not entered)' }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const showDropdown = ref(false)
const search = ref('')
const phone = ref('(555) 123-4567')

const countries = [
  { name: 'United States', code: '+1', flag: '🇺🇸' },
  { name: 'United Kingdom', code: '+44', flag: '🇬🇧' },
  { name: 'France', code: '+33', flag: '🇫🇷' },
  { name: 'Germany', code: '+49', flag: '🇩🇪' },
  { name: 'Japan', code: '+81', flag: '🇯🇵' },
  { name: 'China', code: '+86', flag: '🇨🇳' },
  { name: 'Australia', code: '+61', flag: '🇦🇺' },
  { name: 'Canada', code: '+1', flag: '🇨🇦' },
  { name: 'Brazil', code: '+55', flag: '🇧🇷' },
  { name: 'India', code: '+91', flag: '🇮🇳' }
]

const selectedCountry = ref(countries[0])

const filteredCountries = computed(() => {
  if (!search.value) return countries
  const query = search.value.toLowerCase()
  return countries.filter(c =>
    c.name.toLowerCase().includes(query) ||
    c.code.includes(query)
  )
})

const selectCountry = (country) => {
  selectedCountry.value = country
  showDropdown.value = false
  search.value = ''
}

const formatPhone = (event) => {
  const digits = event.target.value.replace(/\D/g, '')
  let formatted = digits
  if (digits.length >= 3) {
    formatted = `(${digits.slice(0, 3)}`
    if (digits.length >= 6) {
      formatted += `) ${digits.slice(3, 6)}`
      if (digits.length >= 10) {
        formatted += `-${digits.slice(6, 10)}`
      } else if (digits.length > 6) {
        formatted += `-${digits.slice(6)}`
      }
    } else if (digits.length > 3) {
      formatted += `) ${digits.slice(3)}`
    }
  }
  phone.value = formatted
}
</script>

<style scoped>
.demo-phone-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-phone-input {
  display: flex;
  position: relative;
  width: 100%;
  max-width: 20rem;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  overflow: visible;
}

.demo-phone-input:focus-within {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-phone-input__country {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.5rem;
  background: #f9fafb;
  border: none;
  border-right: 1px solid #d1d5db;
  cursor: pointer;
}

.demo-phone-input__country:hover {
  background: #f3f4f6;
}

.demo-phone-input__flag {
  font-size: 1.25rem;
}

.demo-phone-input__code {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-phone-input__country svg {
  width: 0.75rem;
  height: 0.75rem;
  color: #6b7280;
}

.demo-phone-input__input {
  flex: 1;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  border: none;
  outline: none;
}

.demo-phone-input__dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  margin-top: 0.25rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  z-index: 50;
}

.demo-phone-input__search {
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  border: none;
  border-bottom: 1px solid #e5e7eb;
  outline: none;
}

.demo-phone-input__options {
  max-height: 12rem;
  overflow-y: auto;
}

.demo-phone-input__option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  text-align: left;
  background: none;
  border: none;
  cursor: pointer;
}

.demo-phone-input__option:hover {
  background: #f3f4f6;
}

.demo-phone-input__option-code {
  margin-left: auto;
  color: #6b7280;
  font-size: 0.75rem;
}
</style>
