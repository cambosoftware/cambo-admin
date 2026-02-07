<template>
  <div :class="['demo-input-wrapper', `demo-input-wrapper--${size}`]">
    <span v-if="prefix" class="demo-input__prefix">{{ prefix }}</span>
    <div class="demo-input__icon-left" v-if="$slots.iconLeft">
      <slot name="iconLeft"></slot>
    </div>
    <input
      :class="[
        'demo-input',
        {
          'demo-input--has-prefix': prefix,
          'demo-input--has-suffix': suffix,
          'demo-input--has-icon-left': $slots.iconLeft,
          'demo-input--has-icon-right': $slots.iconRight || clearable,
          'demo-input--error': error,
          'demo-input--disabled': disabled
        }
      ]"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :readonly="readonly"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <button
      v-if="clearable && modelValue"
      class="demo-input__clear"
      @click="$emit('update:modelValue', '')"
    >
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>
    <div class="demo-input__icon-right" v-else-if="$slots.iconRight">
      <slot name="iconRight"></slot>
    </div>
    <span v-if="suffix" class="demo-input__suffix">{{ suffix }}</span>
  </div>
</template>

<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: 'Enter text...' },
  size: { type: String, default: 'md' },
  prefix: { type: String, default: '' },
  suffix: { type: String, default: '' },
  clearable: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  readonly: { type: Boolean, default: false },
  error: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-input-wrapper {
  display: inline-flex;
  align-items: center;
  width: 100%;
  max-width: 20rem;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  overflow: hidden;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.demo-input-wrapper:focus-within {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-input-wrapper--sm { font-size: 0.875rem; }
.demo-input-wrapper--md { font-size: 0.875rem; }
.demo-input-wrapper--lg { font-size: 1rem; }

.demo-input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: inherit;
}

.demo-input-wrapper--sm .demo-input { padding: 0.375rem 0.75rem; }
.demo-input-wrapper--md .demo-input { padding: 0.5rem 0.75rem; }
.demo-input-wrapper--lg .demo-input { padding: 0.625rem 1rem; }

.demo-input--has-icon-left { padding-left: 0.5rem !important; }
.demo-input--has-icon-right { padding-right: 0.5rem !important; }
.demo-input--has-prefix { padding-left: 0.5rem !important; }
.demo-input--has-suffix { padding-right: 0.5rem !important; }

.demo-input--disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.demo-input__prefix,
.demo-input__suffix {
  padding: 0 0.75rem;
  color: #6b7280;
  background: #f3f4f6;
  font-size: inherit;
  white-space: nowrap;
}

.demo-input__icon-left,
.demo-input__icon-right {
  display: flex;
  padding: 0 0.5rem;
  color: #9ca3af;
}

.demo-input__icon-left svg,
.demo-input__icon-right svg {
  width: 1.25rem;
  height: 1.25rem;
}

.demo-input__clear {
  display: flex;
  padding: 0.25rem;
  margin-right: 0.25rem;
  background: transparent;
  border: none;
  color: #9ca3af;
  cursor: pointer;
}

.demo-input__clear:hover { color: #6b7280; }
.demo-input__clear svg { width: 1rem; height: 1rem; }

.demo-input-wrapper:has(.demo-input--error) {
  border-color: #ef4444;
}
</style>
