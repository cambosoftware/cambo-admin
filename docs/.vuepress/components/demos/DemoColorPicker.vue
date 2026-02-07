<template>
  <div class="demo-color-picker-showcase">
    <span class="demo-label">Pick a color:</span>
    <div class="demo-color-picker">
      <div class="demo-color-picker__trigger" @click="isOpen = !isOpen">
        <div class="demo-color-picker__preview" :style="{ background: selectedColor }"></div>
        <span class="demo-color-picker__value">{{ selectedColor }}</span>
      </div>
      <div v-if="isOpen" class="demo-color-picker__dropdown">
        <div class="demo-color-picker__presets">
          <button
            v-for="color in presets"
            :key="color"
            :class="['demo-color-picker__preset', { 'demo-color-picker__preset--selected': selectedColor === color }]"
            :style="{ background: color }"
            @click="selectedColor = color"
          >
            <svg v-if="selectedColor === color" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </button>
        </div>
        <div class="demo-color-picker__custom">
          <input type="color" :value="selectedColor" @input="selectedColor = $event.target.value" />
          <input type="text" v-model="selectedColor" />
        </div>
      </div>
    </div>
    <div class="demo-color-preview" :style="{ background: selectedColor }">
      Preview: {{ selectedColor }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const selectedColor = ref('#6366f1')
const isOpen = ref(false)

const presets = [
  '#ef4444', '#f97316', '#eab308', '#22c55e', '#10b981',
  '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1',
  '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e'
]
</script>

<style scoped>
.demo-color-picker-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; max-width: 16rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-color-picker { position: relative; }
.demo-color-picker__trigger { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; }
.demo-color-picker__preview { width: 1.5rem; height: 1.5rem; border-radius: 0.25rem; border: 1px solid rgba(0,0,0,0.1); }
.demo-color-picker__value { font-size: 0.875rem; font-family: monospace; color: #374151; }
.demo-color-picker__dropdown { position: absolute; top: 100%; left: 0; margin-top: 0.25rem; padding: 0.75rem; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 50; }
.demo-color-picker__presets { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.375rem; margin-bottom: 0.75rem; }
.demo-color-picker__preset { width: 1.75rem; height: 1.75rem; border: 2px solid transparent; border-radius: 0.25rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.demo-color-picker__preset:hover { transform: scale(1.1); }
.demo-color-picker__preset--selected { border-color: white; box-shadow: 0 0 0 2px #374151; }
.demo-color-picker__preset svg { width: 1rem; height: 1rem; color: white; }
.demo-color-picker__custom { display: flex; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; }
.demo-color-picker__custom input[type="color"] { width: 2rem; height: 2rem; padding: 0; border: none; border-radius: 0.25rem; cursor: pointer; }
.demo-color-picker__custom input[type="text"] { flex: 1; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-family: monospace; border: 1px solid #d1d5db; border-radius: 0.25rem; }
.demo-color-preview { padding: 1rem; border-radius: 0.375rem; font-size: 0.875rem; color: white; text-align: center; text-shadow: 0 1px 2px rgba(0,0,0,0.2); }
</style>
