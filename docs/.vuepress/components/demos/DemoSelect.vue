<template>
  <div :class="['demo-select-wrapper', `demo-select-wrapper--${size}`]">
    <select
      :class="[
        'demo-select',
        { 'demo-select--error': error, 'demo-select--disabled': disabled }
      ]"
      :value="modelValue"
      :disabled="disabled"
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in normalizedOptions"
        :key="option.value"
        :value="option.value"
        :disabled="option.disabled"
      >
        {{ option.label }}
      </option>
    </select>
    <span class="demo-select__arrow">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: 'Select an option' },
  size: { type: String, default: 'md' },
  disabled: { type: Boolean, default: false },
  error: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])

const normalizedOptions = computed(() => {
  return props.options.map(opt => {
    if (typeof opt === 'object') return opt
    return { value: opt, label: opt }
  })
})
</script>

<style scoped>
.demo-select-wrapper {
  position: relative;
  display: inline-block;
  width: 100%;
  max-width: 20rem;
}

.demo-select {
  width: 100%;
  appearance: none;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.demo-select-wrapper--sm .demo-select { padding: 0.375rem 2rem 0.375rem 0.75rem; font-size: 0.875rem; }
.demo-select-wrapper--md .demo-select { padding: 0.5rem 2.5rem 0.5rem 0.75rem; font-size: 0.875rem; }
.demo-select-wrapper--lg .demo-select { padding: 0.625rem 2.5rem 0.625rem 1rem; font-size: 1rem; }

.demo-select:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-select--disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.demo-select--error {
  border-color: #ef4444;
}

.demo-select__arrow {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  color: #9ca3af;
}

.demo-select__arrow svg {
  width: 1rem;
  height: 1rem;
}
</style>
