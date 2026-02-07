<template>
  <div class="demo-accordion">
    <div
      v-for="(item, index) in items"
      :key="index"
      :class="['demo-accordion__item', { 'demo-accordion__item--open': openItems.includes(index) }]"
    >
      <button class="demo-accordion__header" @click="toggle(index)">
        <span>{{ item.title }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </button>
      <div class="demo-accordion__content" v-show="openItems.includes(index)">
        <div class="demo-accordion__body">
          {{ item.content }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    default: () => [
      { title: 'Section 1', content: 'Content for section 1' },
      { title: 'Section 2', content: 'Content for section 2' },
      { title: 'Section 3', content: 'Content for section 3' }
    ]
  },
  multiple: { type: Boolean, default: false },
  defaultOpen: { type: Array, default: () => [0] }
})

const openItems = ref([...props.defaultOpen])

const toggle = (index) => {
  if (props.multiple) {
    const i = openItems.value.indexOf(index)
    if (i > -1) {
      openItems.value.splice(i, 1)
    } else {
      openItems.value.push(index)
    }
  } else {
    openItems.value = openItems.value.includes(index) ? [] : [index]
  }
}
</script>

<style scoped>
.demo-accordion {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
}

.demo-accordion__item {
  border-bottom: 1px solid #e5e7eb;
}
.demo-accordion__item:last-child {
  border-bottom: none;
}

.demo-accordion__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  text-align: left;
  background: white;
  border: none;
  cursor: pointer;
  transition: background 0.15s;
}
.demo-accordion__header:hover { background: #f9fafb; }
.demo-accordion__header svg {
  width: 1rem;
  height: 1rem;
  color: #9ca3af;
  transition: transform 0.2s;
}

.demo-accordion__item--open .demo-accordion__header svg {
  transform: rotate(180deg);
}

.demo-accordion__body {
  padding: 0 1rem 1rem;
  font-size: 0.875rem;
  color: #6b7280;
}
</style>
