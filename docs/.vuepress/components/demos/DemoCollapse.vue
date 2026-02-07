<template>
  <div class="demo-collapse">
    <button class="demo-collapse__trigger" @click="isOpen = !isOpen">
      <slot name="trigger">
        <span>Toggle Content</span>
        <svg :class="{ 'demo-collapse__icon--open': isOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </slot>
    </button>
    <Transition name="collapse">
      <div v-show="isOpen" class="demo-collapse__content">
        <div class="demo-collapse__body">
          <slot>Collapsible content goes here</slot>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  defaultOpen: { type: Boolean, default: false }
})

const isOpen = ref(props.defaultOpen)
</script>

<style scoped>
.demo-collapse {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
}

.demo-collapse__trigger {
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
.demo-collapse__trigger:hover { background: #f9fafb; }
.demo-collapse__trigger svg {
  width: 1rem;
  height: 1rem;
  color: #9ca3af;
  transition: transform 0.2s;
}

.demo-collapse__icon--open {
  transform: rotate(180deg);
}

.demo-collapse__body {
  padding: 1rem;
  font-size: 0.875rem;
  color: #6b7280;
  border-top: 1px solid #e5e7eb;
}

.collapse-enter-active,
.collapse-leave-active {
  transition: all 0.2s ease;
  overflow: hidden;
}
.collapse-enter-from,
.collapse-leave-to {
  opacity: 0;
}
</style>
