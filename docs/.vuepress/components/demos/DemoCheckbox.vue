<template>
  <label :class="['demo-checkbox', { 'demo-checkbox--disabled': disabled }]">
    <input
      type="checkbox"
      class="demo-checkbox__input"
      :checked="modelValue"
      :disabled="disabled"
      :indeterminate="indeterminate"
      @change="$emit('update:modelValue', $event.target.checked)"
    />
    <span :class="['demo-checkbox__box', `demo-checkbox__box--${color}`]">
      <svg v-if="modelValue && !indeterminate" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      <svg v-if="indeterminate" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
        <line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
    </span>
    <span v-if="label || $slots.default" class="demo-checkbox__label">
      <slot>{{ label }}</slot>
    </span>
  </label>
</template>

<script setup>
defineProps({
  modelValue: { type: Boolean, default: false },
  label: { type: String, default: '' },
  color: { type: String, default: 'primary' },
  disabled: { type: Boolean, default: false },
  indeterminate: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-checkbox {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  user-select: none;
}

.demo-checkbox--disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.demo-checkbox__input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.demo-checkbox__box {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 0.25rem;
  background: white;
  transition: all 0.15s;
}

.demo-checkbox__box svg {
  width: 0.75rem;
  height: 0.75rem;
  color: white;
}

.demo-checkbox__input:checked + .demo-checkbox__box--primary,
.demo-checkbox__input:indeterminate + .demo-checkbox__box--primary {
  background: #6366f1;
  border-color: #6366f1;
}

.demo-checkbox__input:checked + .demo-checkbox__box--success,
.demo-checkbox__input:indeterminate + .demo-checkbox__box--success {
  background: #10b981;
  border-color: #10b981;
}

.demo-checkbox__input:checked + .demo-checkbox__box--danger,
.demo-checkbox__input:indeterminate + .demo-checkbox__box--danger {
  background: #ef4444;
  border-color: #ef4444;
}

.demo-checkbox__input:focus + .demo-checkbox__box {
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-checkbox__label {
  font-size: 0.875rem;
  color: #374151;
}
</style>
