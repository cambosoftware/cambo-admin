<template>
  <label :class="['demo-radio', { 'demo-radio--disabled': disabled }]">
    <input
      type="radio"
      class="demo-radio__input"
      :checked="modelValue === value"
      :disabled="disabled"
      :name="name"
      @change="$emit('update:modelValue', value)"
    />
    <span :class="['demo-radio__circle', `demo-radio__circle--${color}`]">
      <span class="demo-radio__dot"></span>
    </span>
    <span v-if="label || $slots.default" class="demo-radio__label">
      <slot>{{ label }}</slot>
    </span>
  </label>
</template>

<script setup>
defineProps({
  modelValue: { type: [String, Number, Boolean], default: '' },
  value: { type: [String, Number, Boolean], default: '' },
  label: { type: String, default: '' },
  name: { type: String, default: '' },
  color: { type: String, default: 'primary' },
  disabled: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-radio {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  user-select: none;
}

.demo-radio--disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.demo-radio__input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.demo-radio__circle {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 9999px;
  background: white;
  transition: all 0.15s;
}

.demo-radio__dot {
  width: 0.5rem;
  height: 0.5rem;
  border-radius: 9999px;
  transform: scale(0);
  transition: transform 0.15s;
}

.demo-radio__input:checked + .demo-radio__circle--primary {
  border-color: #6366f1;
}
.demo-radio__input:checked + .demo-radio__circle--primary .demo-radio__dot {
  background: #6366f1;
  transform: scale(1);
}

.demo-radio__input:checked + .demo-radio__circle--success {
  border-color: #10b981;
}
.demo-radio__input:checked + .demo-radio__circle--success .demo-radio__dot {
  background: #10b981;
  transform: scale(1);
}

.demo-radio__input:focus + .demo-radio__circle {
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-radio__label {
  font-size: 0.875rem;
  color: #374151;
}
</style>
