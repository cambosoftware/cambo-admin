<template>
  <div class="demo-tabs">
    <div :class="['demo-tabs__list', `demo-tabs__list--${variant}`]">
      <button
        v-for="(tab, index) in tabs"
        :key="index"
        :class="['demo-tabs__tab', { 'demo-tabs__tab--active': activeTab === index }]"
        @click="activeTab = index"
      >
        {{ tab.label }}
      </button>
    </div>
    <div class="demo-tabs__content">
      {{ tabs[activeTab]?.content || 'Tab content' }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  tabs: {
    type: Array,
    default: () => [
      { label: 'Tab 1', content: 'Content for Tab 1' },
      { label: 'Tab 2', content: 'Content for Tab 2' },
      { label: 'Tab 3', content: 'Content for Tab 3' }
    ]
  },
  variant: { type: String, default: 'default' },
  defaultTab: { type: Number, default: 0 }
})

const activeTab = ref(props.defaultTab)
</script>

<style scoped>
.demo-tabs {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
}

.demo-tabs__list {
  display: flex;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.demo-tabs__list--pills {
  gap: 0.5rem;
  padding: 0.5rem;
  background: white;
}

.demo-tabs__tab {
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.15s;
  position: relative;
}

.demo-tabs__list--default .demo-tabs__tab--active {
  color: #6366f1;
  background: white;
}
.demo-tabs__list--default .demo-tabs__tab--active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: #6366f1;
}

.demo-tabs__list--pills .demo-tabs__tab {
  border-radius: 0.375rem;
}
.demo-tabs__list--pills .demo-tabs__tab--active {
  color: white;
  background: #6366f1;
}

.demo-tabs__content {
  padding: 1rem;
  font-size: 0.875rem;
  color: #374151;
  background: white;
}
</style>
