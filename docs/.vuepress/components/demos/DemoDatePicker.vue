<template>
  <div class="demo-date-picker-showcase">
    <span class="demo-label">Select a date:</span>
    <div class="demo-date-picker">
      <div class="demo-date-picker__input" @click="isOpen = !isOpen">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span v-if="selectedDate">{{ formatDate(selectedDate) }}</span>
        <span v-else class="demo-placeholder">Pick a date...</span>
      </div>
      <div v-if="isOpen" class="demo-date-picker__dropdown">
        <div class="demo-date-picker__header">
          <button @click="prevMonth">&lt;</button>
          <span>{{ monthNames[currentMonth] }} {{ currentYear }}</span>
          <button @click="nextMonth">&gt;</button>
        </div>
        <div class="demo-date-picker__weekdays">
          <span v-for="d in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="d">{{ d }}</span>
        </div>
        <div class="demo-date-picker__days">
          <button
            v-for="day in calendarDays"
            :key="day.key"
            :class="['demo-date-picker__day', { 'demo-date-picker__day--other': day.other, 'demo-date-picker__day--selected': isSelected(day), 'demo-date-picker__day--today': isToday(day) }]"
            @click="selectDate(day)"
          >
            {{ day.date }}
          </button>
        </div>
      </div>
    </div>
    <span class="demo-selected">Selected: {{ selectedDate ? formatDate(selectedDate) : 'None' }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const selectedDate = ref(null)
const isOpen = ref(false)
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())
const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December']

const calendarDays = computed(() => {
  const days = []
  const firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay()
  const lastDate = new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
  const prevLastDate = new Date(currentYear.value, currentMonth.value, 0).getDate()

  for (let i = firstDay - 1; i >= 0; i--) {
    days.push({ key: `p${i}`, date: prevLastDate - i, other: true, month: currentMonth.value - 1 })
  }
  for (let i = 1; i <= lastDate; i++) {
    days.push({ key: `c${i}`, date: i, other: false, month: currentMonth.value })
  }
  const remaining = 42 - days.length
  for (let i = 1; i <= remaining; i++) {
    days.push({ key: `n${i}`, date: i, other: true, month: currentMonth.value + 1 })
  }
  return days
})

const prevMonth = () => { if (currentMonth.value === 0) { currentMonth.value = 11; currentYear.value-- } else currentMonth.value-- }
const nextMonth = () => { if (currentMonth.value === 11) { currentMonth.value = 0; currentYear.value++ } else currentMonth.value++ }

const selectDate = (day) => {
  selectedDate.value = new Date(currentYear.value, day.month, day.date)
  isOpen.value = false
}

const isSelected = (day) => {
  if (!selectedDate.value) return false
  return selectedDate.value.getDate() === day.date && selectedDate.value.getMonth() === day.month
}

const isToday = (day) => {
  const today = new Date()
  return today.getDate() === day.date && today.getMonth() === day.month && today.getFullYear() === currentYear.value && !day.other
}

const formatDate = (date) => date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
</script>

<style scoped>
.demo-date-picker-showcase { display: flex; flex-direction: column; gap: 0.5rem; width: 100%; max-width: 16rem; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }
.demo-date-picker { position: relative; }
.demo-date-picker__input { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; }
.demo-date-picker__input svg { width: 1.25rem; height: 1.25rem; color: #6b7280; }
.demo-date-picker__input span { font-size: 0.875rem; color: #374151; }
.demo-placeholder { color: #9ca3af !important; }
.demo-date-picker__dropdown { position: absolute; top: 100%; left: 0; margin-top: 0.25rem; padding: 0.75rem; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 50; }
.demo-date-picker__header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
.demo-date-picker__header button { padding: 0.25rem 0.5rem; background: none; border: none; cursor: pointer; border-radius: 0.25rem; }
.demo-date-picker__header button:hover { background: #f3f4f6; }
.demo-date-picker__header span { font-size: 0.875rem; font-weight: 600; }
.demo-date-picker__weekdays { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.125rem; margin-bottom: 0.25rem; }
.demo-date-picker__weekdays span { font-size: 0.75rem; font-weight: 500; color: #6b7280; text-align: center; padding: 0.25rem; }
.demo-date-picker__days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.125rem; }
.demo-date-picker__day { width: 2rem; height: 2rem; font-size: 0.75rem; background: none; border: none; border-radius: 0.25rem; cursor: pointer; }
.demo-date-picker__day:hover { background: #f3f4f6; }
.demo-date-picker__day--other { color: #9ca3af; }
.demo-date-picker__day--today { font-weight: 600; color: #6366f1; }
.demo-date-picker__day--selected { background: #6366f1 !important; color: white !important; }
</style>
