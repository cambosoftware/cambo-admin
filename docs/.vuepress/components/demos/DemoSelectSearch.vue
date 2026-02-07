<template>
  <div class="demo-select-search-showcase">
    <span class="demo-label">Select a country:</span>
    <div class="demo-select-search" ref="container">
      <div class="demo-select-search__trigger" @click="isOpen = !isOpen">
        <span v-if="selectedLabel" class="demo-select-search__value">{{ selectedLabel }}</span>
        <span v-else class="demo-select-search__placeholder">Search and select...</span>
        <svg class="demo-select-search__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </div>
      <div v-if="isOpen" class="demo-select-search__dropdown">
        <div class="demo-select-search__search">
          <input
            ref="searchInput"
            v-model="search"
            type="text"
            placeholder="Type to search..."
          />
        </div>
        <div class="demo-select-search__options">
          <div
            v-for="option in filteredOptions"
            :key="option.value"
            :class="['demo-select-search__option', { 'demo-select-search__option--selected': selected === option.value }]"
            @click="selectOption(option)"
          >
            <span>{{ option.flag }} {{ option.label }}</span>
            <svg v-if="selected === option.value" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div v-if="filteredOptions.length === 0" class="demo-select-search__empty">
            No results found
          </div>
        </div>
      </div>
    </div>
    <span class="demo-selected">Selected: {{ selected || 'None' }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const options = [
  { value: 'us', label: 'United States', flag: '🇺🇸' },
  { value: 'uk', label: 'United Kingdom', flag: '🇬🇧' },
  { value: 'fr', label: 'France', flag: '🇫🇷' },
  { value: 'de', label: 'Germany', flag: '🇩🇪' },
  { value: 'jp', label: 'Japan', flag: '🇯🇵' },
  { value: 'ca', label: 'Canada', flag: '🇨🇦' },
  { value: 'au', label: 'Australia', flag: '🇦🇺' },
  { value: 'br', label: 'Brazil', flag: '🇧🇷' }
]

const selected = ref('fr')
const isOpen = ref(false)
const search = ref('')

const selectedLabel = computed(() => {
  const opt = options.find(o => o.value === selected.value)
  return opt ? `${opt.flag} ${opt.label}` : ''
})

const filteredOptions = computed(() => {
  if (!search.value) return options
  return options.filter(o => o.label.toLowerCase().includes(search.value.toLowerCase()))
})

const selectOption = (option) => {
  selected.value = option.value
  isOpen.value = false
  search.value = ''
}
</script>

<style scoped>
.demo-select-search-showcase {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
  max-width: 16rem;
}

.demo-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.demo-selected {
  font-size: 0.75rem;
  color: #6b7280;
}

.demo-select-search {
  position: relative;
}

.demo-select-search__trigger {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0.75rem;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
}

.demo-select-search__value {
  font-size: 0.875rem;
  color: #374151;
}

.demo-select-search__placeholder {
  font-size: 0.875rem;
  color: #9ca3af;
}

.demo-select-search__arrow {
  width: 1rem;
  height: 1rem;
  color: #6b7280;
}

.demo-select-search__dropdown {
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

.demo-select-search__search input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  border: none;
  border-bottom: 1px solid #e5e7eb;
  outline: none;
}

.demo-select-search__options {
  max-height: 12rem;
  overflow-y: auto;
}

.demo-select-search__option {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  cursor: pointer;
}

.demo-select-search__option:hover {
  background: #f3f4f6;
}

.demo-select-search__option--selected {
  background: #eef2ff;
  color: #4f46e5;
}

.demo-select-search__option svg {
  width: 1rem;
  height: 1rem;
}

.demo-select-search__empty {
  padding: 1rem;
  text-align: center;
  font-size: 0.875rem;
  color: #6b7280;
}
</style>
