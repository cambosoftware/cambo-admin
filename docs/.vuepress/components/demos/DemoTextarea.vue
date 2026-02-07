<template>
  <div class="demo-textarea-wrapper">
    <textarea
      :class="[
        'demo-textarea',
        `demo-textarea--${resize}`,
        { 'demo-textarea--error': error, 'demo-textarea--disabled': disabled }
      ]"
      :value="modelValue"
      :placeholder="placeholder"
      :rows="rows"
      :disabled="disabled"
      :readonly="readonly"
      :maxlength="maxlength"
      @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
    <div v-if="showCount && maxlength" class="demo-textarea__count">
      {{ (modelValue || '').length }} / {{ maxlength }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  modelValue: { type: String, default: '' },
  placeholder: { type: String, default: 'Enter text...' },
  rows: { type: Number, default: 4 },
  resize: { type: String, default: 'vertical' },
  maxlength: { type: Number, default: null },
  showCount: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  readonly: { type: Boolean, default: false },
  error: { type: Boolean, default: false }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-textarea-wrapper {
  display: inline-block;
  width: 100%;
  max-width: 20rem;
}

.demo-textarea {
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background: white;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.demo-textarea:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.demo-textarea--none { resize: none; }
.demo-textarea--vertical { resize: vertical; }
.demo-textarea--horizontal { resize: horizontal; }
.demo-textarea--both { resize: both; }

.demo-textarea--disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.demo-textarea--error {
  border-color: #ef4444;
}

.demo-textarea__count {
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: #6b7280;
  text-align: right;
}
</style>
