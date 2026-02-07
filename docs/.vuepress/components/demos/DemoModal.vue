<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="demo-modal-backdrop" @click.self="closeOnBackdrop && $emit('update:modelValue', false)">
        <div :class="['demo-modal', `demo-modal--${size}`]">
          <div class="demo-modal__header">
            <h3 class="demo-modal__title">{{ title }}</h3>
            <button v-if="closable" class="demo-modal__close" @click="$emit('update:modelValue', false)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="demo-modal__body">
            <slot>Modal content goes here</slot>
          </div>
          <div v-if="$slots.footer" class="demo-modal__footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, default: 'Modal Title' },
  size: { type: String, default: 'md' },
  closable: { type: Boolean, default: true },
  closeOnBackdrop: { type: Boolean, default: true }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-modal-backdrop {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.5);
  z-index: 9999;
  padding: 1rem;
}

.demo-modal {
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

.demo-modal--sm { width: 100%; max-width: 24rem; }
.demo-modal--md { width: 100%; max-width: 32rem; }
.demo-modal--lg { width: 100%; max-width: 48rem; }
.demo-modal--xl { width: 100%; max-width: 64rem; }
.demo-modal--full { width: 100%; max-width: calc(100vw - 2rem); }

.demo-modal__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.demo-modal__title {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.demo-modal__close {
  display: flex;
  padding: 0.25rem;
  background: transparent;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  border-radius: 0.25rem;
}
.demo-modal__close:hover { color: #6b7280; background: #f3f4f6; }
.demo-modal__close svg { width: 1.25rem; height: 1.25rem; }

.demo-modal__body {
  padding: 1.5rem;
  overflow-y: auto;
}

.demo-modal__footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
.modal-enter-from .demo-modal,
.modal-leave-to .demo-modal {
  transform: scale(0.95) translateY(-1rem);
}
</style>
