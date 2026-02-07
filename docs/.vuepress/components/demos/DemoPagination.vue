<template>
  <nav class="demo-pagination">
    <button
      class="demo-pagination__btn"
      :disabled="currentPage === 1"
      @click="goTo(currentPage - 1)"
    >
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </button>

    <template v-for="page in pages" :key="page">
      <span v-if="page === '...'" class="demo-pagination__ellipsis">...</span>
      <button
        v-else
        :class="['demo-pagination__page', { 'demo-pagination__page--active': page === currentPage }]"
        @click="goTo(page)"
      >
        {{ page }}
      </button>
    </template>

    <button
      class="demo-pagination__btn"
      :disabled="currentPage === totalPages"
      @click="goTo(currentPage + 1)"
    >
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
    </button>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  total: { type: Number, default: 100 },
  perPage: { type: Number, default: 10 },
  modelValue: { type: Number, default: 1 }
})

const emit = defineEmits(['update:modelValue'])

const currentPage = ref(props.modelValue)

const totalPages = computed(() => Math.ceil(props.total / props.perPage))

const pages = computed(() => {
  const result = []
  const total = totalPages.value
  const current = currentPage.value

  if (total <= 7) {
    for (let i = 1; i <= total; i++) result.push(i)
  } else {
    result.push(1)
    if (current > 3) result.push('...')
    for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
      result.push(i)
    }
    if (current < total - 2) result.push('...')
    result.push(total)
  }

  return result
})

const goTo = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    emit('update:modelValue', page)
  }
}
</script>

<style scoped>
.demo-pagination {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.demo-pagination__btn,
.demo-pagination__page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 2rem;
  height: 2rem;
  padding: 0 0.5rem;
  font-size: 0.875rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.15s;
}

.demo-pagination__btn:hover:not(:disabled),
.demo-pagination__page:hover {
  background: #f3f4f6;
}

.demo-pagination__btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.demo-pagination__btn svg {
  width: 1rem;
  height: 1rem;
}

.demo-pagination__page--active {
  background: #6366f1;
  border-color: #6366f1;
  color: white;
}
.demo-pagination__page--active:hover {
  background: #4f46e5;
}

.demo-pagination__ellipsis {
  padding: 0 0.25rem;
  color: #9ca3af;
}
</style>
