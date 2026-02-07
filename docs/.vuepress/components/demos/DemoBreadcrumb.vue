<template>
  <nav class="demo-breadcrumb">
    <ol class="demo-breadcrumb__list">
      <li
        v-for="(item, index) in items"
        :key="index"
        :class="['demo-breadcrumb__item', { 'demo-breadcrumb__item--active': index === items.length - 1 }]"
      >
        <a
          v-if="index < items.length - 1"
          :href="item.href"
          class="demo-breadcrumb__link"
          @click.prevent
        >
          {{ item.label }}
        </a>
        <span v-else class="demo-breadcrumb__current">{{ item.label }}</span>
        <span v-if="index < items.length - 1" class="demo-breadcrumb__separator">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    default: () => [
      { label: 'Home', href: '/' },
      { label: 'Products', href: '/products' },
      { label: 'Category', href: '/products/category' },
      { label: 'Current Page' }
    ]
  }
})
</script>

<style scoped>
.demo-breadcrumb__list {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  padding: 0;
  margin: 0;
  list-style: none;
}

.demo-breadcrumb__item {
  display: flex;
  align-items: center;
}

.demo-breadcrumb__link {
  font-size: 0.875rem;
  color: #6b7280;
  text-decoration: none;
  transition: color 0.15s;
}
.demo-breadcrumb__link:hover {
  color: #6366f1;
}

.demo-breadcrumb__current {
  font-size: 0.875rem;
  font-weight: 500;
  color: #111827;
}

.demo-breadcrumb__separator {
  display: flex;
  margin: 0 0.5rem;
  color: #d1d5db;
}
.demo-breadcrumb__separator svg {
  width: 1rem;
  height: 1rem;
}
</style>
