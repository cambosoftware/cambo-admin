<template>
  <label :class="['demo-switch', { 'demo-switch--disabled': disabled }]">
    <span v-if="labelPosition === 'left' && label" class="demo-switch__label">{{ label }}</span>
    <button
      type="button"
      role="switch"
      :aria-checked="modelValue"
      :class="[
        'demo-switch__track',
        `demo-switch__track--${color}`,
        { 'demo-switch__track--on': modelValue }
      ]"
      :disabled="disabled"
      @click="$emit('update:modelValue', !modelValue)"
    >
      <span class="demo-switch__thumb"></span>
    </button>
    <span v-if="labelPosition === 'right' && label" class="demo-switch__label">{{ label }}</span>
  </label>
</template>

<script setup>
defineProps({
  modelValue: { type: Boolean, default: false },
  label: { type: String, default: '' },
  labelPosition: { type: String, default: 'right' },
  color: { type: String, default: 'primary' },
  disabled: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-switch {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.demo-switch--disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.demo-switch__track {
  position: relative;
  width: 2.5rem;
  height: 1.5rem;
  padding: 0;
  background: #d1d5db;
  border: none;
  border-radius: 9999px;
  cursor: pointer;
  transition: background 0.2s;
}

.demo-switch__track--on.demo-switch__track--primary { background: #6366f1; }
.demo-switch__track--on.demo-switch__track--success { background: #10b981; }
.demo-switch__track--on.demo-switch__track--danger { background: #ef4444; }

.demo-switch__thumb {
  position: absolute;
  top: 0.125rem;
  left: 0.125rem;
  width: 1.25rem;
  height: 1.25rem;
  background: white;
  border-radius: 9999px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s;
}

.demo-switch__track--on .demo-switch__thumb {
  transform: translateX(1rem);
}

.demo-switch__label {
  font-size: 0.875rem;
  color: #374151;
}

.demo-switch__track:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-switch__track:disabled {
  cursor: not-allowed;
}
</style>
