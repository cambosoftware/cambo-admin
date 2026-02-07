<template>
  <div class="demo-timeline">
    <div
      v-for="(item, index) in items"
      :key="index"
      :class="['demo-timeline__item', `demo-timeline__item--${item.color || 'primary'}`]"
    >
      <div class="demo-timeline__dot"></div>
      <div class="demo-timeline__line" v-if="index < items.length - 1"></div>
      <div class="demo-timeline__content">
        <div class="demo-timeline__title">{{ item.title }}</div>
        <div v-if="item.time" class="demo-timeline__time">{{ item.time }}</div>
        <div v-if="item.description" class="demo-timeline__description">{{ item.description }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    default: () => [
      { title: 'Order Placed', time: '10:00 AM', description: 'Your order has been placed', color: 'success' },
      { title: 'Processing', time: '10:30 AM', description: 'Order is being processed', color: 'primary' },
      { title: 'Shipped', time: '2:00 PM', description: 'Order has been shipped', color: 'warning' },
      { title: 'Delivered', time: 'Pending', color: 'gray' }
    ]
  }
})
</script>

<style scoped>
.demo-timeline {
  position: relative;
  padding-left: 1.5rem;
}

.demo-timeline__item {
  position: relative;
  padding-bottom: 1.5rem;
}
.demo-timeline__item:last-child {
  padding-bottom: 0;
}

.demo-timeline__dot {
  position: absolute;
  left: -1.5rem;
  top: 0.25rem;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 9999px;
  border: 2px solid;
  background: white;
}

.demo-timeline__item--primary .demo-timeline__dot { border-color: #6366f1; }
.demo-timeline__item--success .demo-timeline__dot { border-color: #10b981; background: #10b981; }
.demo-timeline__item--warning .demo-timeline__dot { border-color: #f59e0b; }
.demo-timeline__item--danger .demo-timeline__dot { border-color: #ef4444; }
.demo-timeline__item--gray .demo-timeline__dot { border-color: #d1d5db; }

.demo-timeline__line {
  position: absolute;
  left: -1.125rem;
  top: 1rem;
  bottom: 0;
  width: 2px;
  background: #e5e7eb;
}

.demo-timeline__content {
  padding-left: 0.5rem;
}

.demo-timeline__title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #111827;
}

.demo-timeline__time {
  font-size: 0.75rem;
  color: #9ca3af;
  margin-top: 0.125rem;
}

.demo-timeline__description {
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 0.25rem;
}
</style>
