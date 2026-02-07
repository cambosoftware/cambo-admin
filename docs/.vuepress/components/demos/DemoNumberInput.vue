<template>
  <div class="demo-number-showcase">
    <div class="demo-number-item">
      <span class="demo-label">Quantity:</span>
      <div class="demo-number-input">
        <button :disabled="quantity <= 0" @click="quantity--">−</button>
        <input type="number" v-model.number="quantity" min="0" max="99" />
        <button :disabled="quantity >= 99" @click="quantity++">+</button>
      </div>
    </div>

    <div class="demo-number-item">
      <span class="demo-label">Guests:</span>
      <div class="demo-number-input">
        <button :disabled="guests <= 1" @click="guests--">−</button>
        <input type="number" v-model.number="guests" min="1" max="10" />
        <button :disabled="guests >= 10" @click="guests++">+</button>
      </div>
      <span class="demo-hint">1-10 guests allowed</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const quantity = ref(3)
const guests = ref(2)
</script>

<style scoped>
.demo-number-showcase { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }
.demo-number-item { display: flex; flex-direction: column; gap: 0.5rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-hint { font-size: 0.75rem; color: #6b7280; }
.demo-number-input { display: inline-flex; align-items: stretch; border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; }
.demo-number-input:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.demo-number-input button { display: flex; align-items: center; justify-content: center; width: 2.5rem; font-size: 1.25rem; background: #f9fafb; border: none; cursor: pointer; transition: background 0.15s; }
.demo-number-input button:hover:not(:disabled) { background: #f3f4f6; }
.demo-number-input button:disabled { opacity: 0.5; cursor: not-allowed; }
.demo-number-input button:first-child { border-right: 1px solid #d1d5db; }
.demo-number-input button:last-child { border-left: 1px solid #d1d5db; }
.demo-number-input input { width: 4rem; padding: 0.5rem; font-size: 0.875rem; text-align: center; border: none; outline: none; -moz-appearance: textfield; }
.demo-number-input input::-webkit-outer-spin-button,
.demo-number-input input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
