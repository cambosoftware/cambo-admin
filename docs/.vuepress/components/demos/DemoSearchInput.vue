<template>
  <div class="demo-search-showcase">
    <span class="demo-label">Search:</span>
    <div :class="['demo-search', { 'demo-search--focused': isFocused }]">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/>
        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input
        v-model="query"
        type="text"
        placeholder="Search users, posts, products..."
        @focus="isFocused = true"
        @blur="isFocused = false"
      />
      <button v-if="query" class="demo-search__clear" @click="query = ''">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div v-if="query && filteredResults.length > 0" class="demo-search__results">
      <div v-for="result in filteredResults" :key="result" class="demo-search__result">
        {{ result }}
      </div>
    </div>
    <span class="demo-selected">Query: "{{ query }}"</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const query = ref('')
const isFocused = ref(false)

const allResults = ['John Doe', 'Jane Smith', 'Product A', 'Product B', 'Blog Post 1', 'User Guide']

const filteredResults = computed(() => {
  if (!query.value) return []
  return allResults.filter(r => r.toLowerCase().includes(query.value.toLowerCase()))
})
</script>

<style scoped>
.demo-search-showcase { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; max-width: 20rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }
.demo-search { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: border-color 0.15s, box-shadow 0.15s; }
.demo-search--focused { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.demo-search > svg { width: 1.25rem; height: 1.25rem; color: #9ca3af; flex-shrink: 0; }
.demo-search input { flex: 1; border: none; outline: none; font-size: 0.875rem; }
.demo-search__clear { display: flex; padding: 0.125rem; background: none; border: none; cursor: pointer; color: #9ca3af; border-radius: 0.25rem; }
.demo-search__clear:hover { color: #6b7280; background: #f3f4f6; }
.demo-search__clear svg { width: 1rem; height: 1rem; }
.demo-search__results { background: white; border: 1px solid #e5e7eb; border-radius: 0.375rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
.demo-search__result { padding: 0.5rem 0.75rem; font-size: 0.875rem; cursor: pointer; }
.demo-search__result:hover { background: #f3f4f6; }
</style>
