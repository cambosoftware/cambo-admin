<template>
  <div class="demo-slider-showcase">
    <div class="demo-slider-item">
      <div class="demo-slider-header">
        <span class="demo-label">Volume</span>
        <span class="demo-value">{{ volume }}%</span>
      </div>
      <div class="demo-slider">
        <div class="demo-slider__track">
          <div class="demo-slider__fill" :style="{ width: volume + '%' }"></div>
        </div>
        <input type="range" v-model.number="volume" min="0" max="100" />
      </div>
    </div>

    <div class="demo-slider-item">
      <div class="demo-slider-header">
        <span class="demo-label">Price</span>
        <span class="demo-value">${{ price }}</span>
      </div>
      <div class="demo-slider">
        <div class="demo-slider__track">
          <div class="demo-slider__fill" :style="{ width: (price / 1000) * 100 + '%' }"></div>
        </div>
        <input type="range" v-model.number="price" min="0" max="1000" step="10" />
      </div>
      <div class="demo-slider-minmax">
        <span>$0</span>
        <span>$1000</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const volume = ref(65)
const price = ref(250)
</script>

<style scoped>
.demo-slider-showcase { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; max-width: 20rem; }
.demo-slider-item { display: flex; flex-direction: column; gap: 0.5rem; }
.demo-slider-header { display: flex; justify-content: space-between; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-value { font-size: 0.875rem; font-weight: 600; color: #6366f1; }
.demo-slider { position: relative; padding: 0.5rem 0; }
.demo-slider__track { position: absolute; top: 50%; left: 0; right: 0; height: 0.5rem; background: #e5e7eb; border-radius: 0.25rem; transform: translateY(-50%); }
.demo-slider__fill { height: 100%; background: #6366f1; border-radius: 0.25rem; }
.demo-slider input { position: relative; width: 100%; appearance: none; background: transparent; cursor: pointer; }
.demo-slider input::-webkit-slider-thumb { appearance: none; width: 1.25rem; height: 1.25rem; background: white; border: 2px solid #6366f1; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.1); cursor: pointer; }
.demo-slider input::-webkit-slider-thumb:hover { transform: scale(1.1); }
.demo-slider-minmax { display: flex; justify-content: space-between; font-size: 0.75rem; color: #6b7280; }
</style>
