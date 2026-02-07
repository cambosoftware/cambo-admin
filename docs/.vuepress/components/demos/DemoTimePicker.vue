<template>
  <div class="demo-time-picker-showcase">
    <span class="demo-label">Select a time:</span>
    <div class="demo-time-picker">
      <div class="demo-time-picker__input" @click="isOpen = !isOpen">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
        <span>{{ formattedTime }}</span>
      </div>
      <div v-if="isOpen" class="demo-time-picker__dropdown">
        <div class="demo-time-picker__columns">
          <div class="demo-time-picker__column">
            <div class="demo-time-picker__label">Hour</div>
            <div class="demo-time-picker__options">
              <button
                v-for="h in 12"
                :key="h"
                :class="['demo-time-picker__option', { 'demo-time-picker__option--selected': hour === h }]"
                @click="hour = h"
              >
                {{ h }}
              </button>
            </div>
          </div>
          <div class="demo-time-picker__column">
            <div class="demo-time-picker__label">Min</div>
            <div class="demo-time-picker__options">
              <button
                v-for="m in minutes"
                :key="m"
                :class="['demo-time-picker__option', { 'demo-time-picker__option--selected': minute === m }]"
                @click="minute = m"
              >
                {{ m.toString().padStart(2, '0') }}
              </button>
            </div>
          </div>
          <div class="demo-time-picker__column demo-time-picker__column--period">
            <div class="demo-time-picker__label">Period</div>
            <button :class="['demo-time-picker__period', { 'demo-time-picker__period--active': period === 'AM' }]" @click="period = 'AM'">AM</button>
            <button :class="['demo-time-picker__period', { 'demo-time-picker__period--active': period === 'PM' }]" @click="period = 'PM'">PM</button>
          </div>
        </div>
        <button class="demo-time-picker__done" @click="isOpen = false">Done</button>
      </div>
    </div>
    <span class="demo-selected">Selected: {{ formattedTime }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const hour = ref(9)
const minute = ref(30)
const period = ref('AM')
const isOpen = ref(false)
const minutes = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55]

const formattedTime = computed(() => `${hour.value}:${minute.value.toString().padStart(2, '0')} ${period.value}`)
</script>

<style scoped>
.demo-time-picker-showcase { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; max-width: 12rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }
.demo-time-picker { position: relative; }
.demo-time-picker__input { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; }
.demo-time-picker__input svg { width: 1.25rem; height: 1.25rem; color: #6b7280; }
.demo-time-picker__input span { font-size: 0.875rem; color: #374151; }
.demo-time-picker__dropdown { position: absolute; top: 100%; left: 0; margin-top: 0.25rem; padding: 0.75rem; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 50; }
.demo-time-picker__columns { display: flex; gap: 0.5rem; }
.demo-time-picker__column { display: flex; flex-direction: column; }
.demo-time-picker__column--period { justify-content: flex-start; }
.demo-time-picker__label { font-size: 0.75rem; font-weight: 500; color: #6b7280; text-align: center; margin-bottom: 0.25rem; }
.demo-time-picker__options { height: 8rem; overflow-y: auto; }
.demo-time-picker__option { display: block; width: 100%; padding: 0.25rem 0.5rem; font-size: 0.875rem; text-align: center; background: none; border: none; border-radius: 0.25rem; cursor: pointer; }
.demo-time-picker__option:hover { background: #f3f4f6; }
.demo-time-picker__option--selected { background: #6366f1 !important; color: white; }
.demo-time-picker__period { display: block; width: 100%; padding: 0.375rem 0.5rem; font-size: 0.75rem; background: #f3f4f6; border: none; border-radius: 0.25rem; cursor: pointer; margin-bottom: 0.25rem; }
.demo-time-picker__period--active { background: #6366f1; color: white; }
.demo-time-picker__done { width: 100%; margin-top: 0.75rem; padding: 0.375rem; font-size: 0.875rem; font-weight: 500; color: white; background: #6366f1; border: none; border-radius: 0.375rem; cursor: pointer; }
</style>
