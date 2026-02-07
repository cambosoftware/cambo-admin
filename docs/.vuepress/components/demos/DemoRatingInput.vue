<template>
  <div class="demo-rating-showcase">
    <div class="demo-rating-item">
      <span class="demo-label">Rate your experience:</span>
      <div class="demo-rating">
        <button
          v-for="star in 5"
          :key="star"
          :class="['demo-rating__star', { 'demo-rating__star--filled': star <= (hovered || rating) }]"
          @mouseenter="hovered = star"
          @mouseleave="hovered = 0"
          @click="rating = star"
        >
          <svg viewBox="0 0 24 24" :fill="star <= (hovered || rating) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </button>
      </div>
      <span class="demo-rating-value">{{ rating }} / 5 stars</span>
    </div>

    <div class="demo-rating-item">
      <span class="demo-label">Product quality:</span>
      <div class="demo-rating demo-rating--small">
        <button
          v-for="star in 5"
          :key="star"
          :class="['demo-rating__star', { 'demo-rating__star--filled': star <= quality }]"
          @click="quality = star"
        >
          <svg viewBox="0 0 24 24" :fill="star <= quality ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const rating = ref(4)
const quality = ref(3)
const hovered = ref(0)
</script>

<style scoped>
.demo-rating-showcase { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }
.demo-rating-item { display: flex; flex-direction: column; gap: 0.5rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-rating-value { font-size: 0.75rem; color: #6b7280; }
.demo-rating { display: inline-flex; gap: 0.25rem; }
.demo-rating--small .demo-rating__star svg { width: 1.25rem; height: 1.25rem; }
.demo-rating__star { padding: 0; background: none; border: none; cursor: pointer; color: #d1d5db; transition: color 0.15s, transform 0.15s; }
.demo-rating__star:hover { transform: scale(1.1); }
.demo-rating__star--filled { color: #fbbf24; }
.demo-rating__star svg { width: 1.75rem; height: 1.75rem; }
</style>
