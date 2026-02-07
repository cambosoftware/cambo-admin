<template>
  <div class="demo-dropdown" ref="dropdownRef">
    <div @click="toggle">
      <slot name="trigger">
        <button class="demo-dropdown__trigger">
          Dropdown
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </button>
      </slot>
    </div>
    <Transition name="dropdown">
      <div v-if="isOpen" :class="['demo-dropdown__menu', `demo-dropdown__menu--${position}`]">
        <slot>
          <button class="demo-dropdown__item">Option 1</button>
          <button class="demo-dropdown__item">Option 2</button>
          <button class="demo-dropdown__item">Option 3</button>
        </slot>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineProps({
  position: { type: String, default: 'bottom-start' }
})

const isOpen = ref(false)
const dropdownRef = ref(null)

const toggle = () => {
  isOpen.value = !isOpen.value
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.demo-dropdown {
  position: relative;
  display: inline-block;
}

.demo-dropdown__trigger {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
}
.demo-dropdown__trigger:hover { background: #f9fafb; }
.demo-dropdown__trigger svg { width: 1rem; height: 1rem; }

.demo-dropdown__menu {
  position: absolute;
  z-index: 50;
  min-width: 10rem;
  padding: 0.25rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.demo-dropdown__menu--bottom-start { top: 100%; left: 0; margin-top: 0.25rem; }
.demo-dropdown__menu--bottom-end { top: 100%; right: 0; margin-top: 0.25rem; }
.demo-dropdown__menu--top-start { bottom: 100%; left: 0; margin-bottom: 0.25rem; }
.demo-dropdown__menu--top-end { bottom: 100%; right: 0; margin-bottom: 0.25rem; }

.demo-dropdown__item {
  display: block;
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  text-align: left;
  background: transparent;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
  color: #374151;
}
.demo-dropdown__item:hover { background: #f3f4f6; }

.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-0.5rem);
}
</style>
