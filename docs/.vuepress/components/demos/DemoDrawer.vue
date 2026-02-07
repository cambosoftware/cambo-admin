<template>
  <Teleport to="body">
    <Transition :name="`drawer-${position}`">
      <div v-if="modelValue" class="demo-drawer-backdrop" @click.self="closeOnBackdrop && $emit('update:modelValue', false)">
        <div :class="['demo-drawer', `demo-drawer--${position}`, `demo-drawer--${size}`]">
          <div class="demo-drawer__header">
            <h3 class="demo-drawer__title">{{ title }}</h3>
            <button class="demo-drawer__close" @click="$emit('update:modelValue', false)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="demo-drawer__body">
            <slot>Drawer content goes here</slot>
          </div>
          <div v-if="$slots.footer" class="demo-drawer__footer">
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
  title: { type: String, default: 'Drawer Title' },
  position: { type: String, default: 'right' },
  size: { type: String, default: 'md' },
  closeOnBackdrop: { type: Boolean, default: true }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.demo-drawer-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 9999;
}

.demo-drawer {
  position: fixed;
  background: white;
  display: flex;
  flex-direction: column;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.demo-drawer--right { top: 0; right: 0; bottom: 0; }
.demo-drawer--left { top: 0; left: 0; bottom: 0; }
.demo-drawer--top { top: 0; left: 0; right: 0; }
.demo-drawer--bottom { bottom: 0; left: 0; right: 0; }

.demo-drawer--right.demo-drawer--sm,
.demo-drawer--left.demo-drawer--sm { width: 20rem; }
.demo-drawer--right.demo-drawer--md,
.demo-drawer--left.demo-drawer--md { width: 28rem; }
.demo-drawer--right.demo-drawer--lg,
.demo-drawer--left.demo-drawer--lg { width: 36rem; }

.demo-drawer--top.demo-drawer--sm,
.demo-drawer--bottom.demo-drawer--sm { height: 25%; }
.demo-drawer--top.demo-drawer--md,
.demo-drawer--bottom.demo-drawer--md { height: 50%; }
.demo-drawer--top.demo-drawer--lg,
.demo-drawer--bottom.demo-drawer--lg { height: 75%; }

.demo-drawer__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.demo-drawer__title {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.demo-drawer__close {
  display: flex;
  padding: 0.25rem;
  background: transparent;
  border: none;
  color: #9ca3af;
  cursor: pointer;
}
.demo-drawer__close:hover { color: #6b7280; }
.demo-drawer__close svg { width: 1.25rem; height: 1.25rem; }

.demo-drawer__body {
  flex: 1;
  padding: 1.5rem;
  overflow-y: auto;
}

.demo-drawer__footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e5e7eb;
}

/* Animations */
.drawer-right-enter-active,
.drawer-right-leave-active,
.drawer-left-enter-active,
.drawer-left-leave-active,
.drawer-top-enter-active,
.drawer-top-leave-active,
.drawer-bottom-enter-active,
.drawer-bottom-leave-active {
  transition: all 0.3s ease;
}

.drawer-right-enter-from .demo-drawer,
.drawer-right-leave-to .demo-drawer { transform: translateX(100%); }
.drawer-left-enter-from .demo-drawer,
.drawer-left-leave-to .demo-drawer { transform: translateX(-100%); }
.drawer-top-enter-from .demo-drawer,
.drawer-top-leave-to .demo-drawer { transform: translateY(-100%); }
.drawer-bottom-enter-from .demo-drawer,
.drawer-bottom-leave-to .demo-drawer { transform: translateY(100%); }

.drawer-right-enter-from,
.drawer-right-leave-to,
.drawer-left-enter-from,
.drawer-left-leave-to,
.drawer-top-enter-from,
.drawer-top-leave-to,
.drawer-bottom-enter-from,
.drawer-bottom-leave-to {
  opacity: 0;
}
</style>
