<template>
  <div
    :class="[
      'demo-avatar',
      `demo-avatar--${size}`,
      { 'demo-avatar--rounded': rounded }
    ]"
    :style="avatarStyle"
  >
    <img v-if="src" :src="src" :alt="alt" class="demo-avatar__img" />
    <span v-else class="demo-avatar__initials">{{ initials }}</span>
    <span v-if="status" :class="['demo-avatar__status', `demo-avatar__status--${status}`]"></span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  src: { type: String, default: '' },
  alt: { type: String, default: '' },
  name: { type: String, default: '' },
  size: { type: String, default: 'md' },
  rounded: { type: Boolean, default: true },
  status: { type: String, default: '' },
  color: { type: String, default: '' }
})

const initials = computed(() => {
  if (!props.name) return '?'
  const parts = props.name.split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return props.name.slice(0, 2).toUpperCase()
})

const avatarStyle = computed(() => {
  if (props.src || !props.name) return {}
  const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4']
  const hash = props.name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0)
  const bgColor = props.color || colors[hash % colors.length]
  return { backgroundColor: bgColor, color: 'white' }
})
</script>

<style scoped>
.demo-avatar {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #e5e7eb;
  color: #6b7280;
  font-weight: 600;
  overflow: hidden;
}

.demo-avatar--xs { width: 1.5rem; height: 1.5rem; font-size: 0.625rem; }
.demo-avatar--sm { width: 2rem; height: 2rem; font-size: 0.75rem; }
.demo-avatar--md { width: 2.5rem; height: 2.5rem; font-size: 0.875rem; }
.demo-avatar--lg { width: 3rem; height: 3rem; font-size: 1rem; }
.demo-avatar--xl { width: 4rem; height: 4rem; font-size: 1.25rem; }
.demo-avatar--2xl { width: 5rem; height: 5rem; font-size: 1.5rem; }

.demo-avatar--rounded { border-radius: 9999px; }
.demo-avatar:not(.demo-avatar--rounded) { border-radius: 0.375rem; }

.demo-avatar__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.demo-avatar__initials {
  text-transform: uppercase;
}

.demo-avatar__status {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 25%;
  height: 25%;
  min-width: 0.5rem;
  min-height: 0.5rem;
  border-radius: 9999px;
  border: 2px solid white;
}

.demo-avatar__status--online { background: #10b981; }
.demo-avatar__status--offline { background: #6b7280; }
.demo-avatar__status--busy { background: #ef4444; }
.demo-avatar__status--away { background: #f59e0b; }
</style>
