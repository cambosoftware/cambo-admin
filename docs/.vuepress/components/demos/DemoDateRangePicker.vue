<template>
  <div class="demo-date-range-showcase">
    <span class="demo-label">Select date range:</span>
    <div class="demo-date-range-picker">
      <div class="demo-date-range-picker__inputs">
        <div class="demo-date-range-picker__input-wrapper" @click="activeInput = 'start'; isOpen = true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <span :class="['demo-date-range-picker__input-text', { 'demo-date-range-picker__input-text--placeholder': !startDate }]">
            {{ startDate ? formatDate(startDate) : 'Start date' }}
          </span>
        </div>
        <span class="demo-date-range-picker__separator">to</span>
        <div class="demo-date-range-picker__input-wrapper" @click="activeInput = 'end'; isOpen = true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <span :class="['demo-date-range-picker__input-text', { 'demo-date-range-picker__input-text--placeholder': !endDate }]">
            {{ endDate ? formatDate(endDate) : 'End date' }}
          </span>
        </div>
      </div>
      <div v-if="isOpen" class="demo-date-range-picker__dropdown">
        <div class="demo-date-range-picker__presets">
          <button v-for="preset in presets" :key="preset.label" @click="applyPreset(preset)">
            {{ preset.label }}
          </button>
        </div>
        <div class="demo-date-range-picker__calendar">
          <div class="demo-date-range-picker__header">
            <button @click="prevMonth">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
              </svg>
            </button>
            <span>{{ monthYear }}</span>
            <button @click="nextMonth">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </button>
          </div>
          <div class="demo-date-range-picker__weekdays">
            <span v-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']" :key="day">{{ day }}</span>
          </div>
          <div class="demo-date-range-picker__days">
            <button
              v-for="day in calendarDays"
              :key="day.key"
              :class="getDayClasses(day)"
              @click="selectDate(day)"
            >
              {{ day.date }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <span class="demo-selected">Selected: {{ startDate && endDate ? `${formatDate(startDate)} - ${formatDate(endDate)}` : 'No range selected' }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const isOpen = ref(false)
const activeInput = ref('start')
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())

// Pre-selected date range (last 7 days)
const today = new Date()
const weekAgo = new Date(today)
weekAgo.setDate(weekAgo.getDate() - 7)
const startDate = ref(weekAgo.toISOString().split('T')[0])
const endDate = ref(today.toISOString().split('T')[0])

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
const monthYear = computed(() => `${months[currentMonth.value]} ${currentYear.value}`)

const presets = [
  { label: 'Today', days: 0 },
  { label: 'Last 7 days', days: 7 },
  { label: 'Last 30 days', days: 30 },
  { label: 'This month', days: 'month' }
]

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const calendarDays = computed(() => {
  const days = []
  const firstDay = new Date(currentYear.value, currentMonth.value, 1)
  const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0)
  const startPadding = firstDay.getDay()

  for (let i = startPadding - 1; i >= 0; i--) {
    const prevLastDay = new Date(currentYear.value, currentMonth.value, 0)
    days.push({ key: `prev-${i}`, date: prevLastDay.getDate() - i, month: currentMonth.value - 1, year: currentYear.value, otherMonth: true })
  }

  for (let i = 1; i <= lastDay.getDate(); i++) {
    days.push({ key: `curr-${i}`, date: i, month: currentMonth.value, year: currentYear.value, otherMonth: false })
  }

  const remaining = 42 - days.length
  for (let i = 1; i <= remaining; i++) {
    days.push({ key: `next-${i}`, date: i, month: currentMonth.value + 1, year: currentYear.value, otherMonth: true })
  }

  return days
})

const prevMonth = () => {
  if (currentMonth.value === 0) { currentMonth.value = 11; currentYear.value-- }
  else { currentMonth.value-- }
}

const nextMonth = () => {
  if (currentMonth.value === 11) { currentMonth.value = 0; currentYear.value++ }
  else { currentMonth.value++ }
}

const getDayClasses = (day) => {
  const classes = ['demo-date-range-picker__day']
  if (day.otherMonth) classes.push('demo-date-range-picker__day--other')

  const dateStr = new Date(day.year, day.month, day.date).toISOString().split('T')[0]
  if (dateStr === startDate.value) classes.push('demo-date-range-picker__day--start')
  if (dateStr === endDate.value) classes.push('demo-date-range-picker__day--end')
  if (startDate.value && endDate.value && dateStr > startDate.value && dateStr < endDate.value) {
    classes.push('demo-date-range-picker__day--in-range')
  }

  return classes
}

const selectDate = (day) => {
  const dateStr = new Date(day.year, day.month, day.date).toISOString().split('T')[0]

  if (activeInput.value === 'start') {
    startDate.value = dateStr
    activeInput.value = 'end'
  } else {
    endDate.value = dateStr
    isOpen.value = false
  }
}

const applyPreset = (preset) => {
  const end = new Date()
  const start = new Date()

  if (preset.days === 'month') {
    start.setDate(1)
  } else {
    start.setDate(start.getDate() - preset.days)
  }

  startDate.value = start.toISOString().split('T')[0]
  endDate.value = end.toISOString().split('T')[0]
  isOpen.value = false
}
</script>

<style scoped>
.demo-date-range-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-date-range-picker { position: relative; }

.demo-date-range-picker__inputs { display: flex; align-items: center; gap: 0.5rem; }

.demo-date-range-picker__input-wrapper {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: border-color 0.15s;
}

.demo-date-range-picker__input-wrapper:hover { border-color: #9ca3af; }
.demo-date-range-picker__input-wrapper svg { width: 1rem; height: 1rem; color: #6b7280; }
.demo-date-range-picker__input-text { font-size: 0.875rem; min-width: 6rem; }
.demo-date-range-picker__input-text--placeholder { color: #9ca3af; }
.demo-date-range-picker__separator { font-size: 0.875rem; color: #6b7280; }

.demo-date-range-picker__dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  margin-top: 0.25rem;
  display: flex;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  z-index: 50;
}

.demo-date-range-picker__presets {
  display: flex;
  flex-direction: column;
  padding: 0.5rem;
  border-right: 1px solid #e5e7eb;
}

.demo-date-range-picker__presets button {
  padding: 0.5rem 0.75rem;
  font-size: 0.75rem;
  text-align: left;
  background: none;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
  white-space: nowrap;
}

.demo-date-range-picker__presets button:hover { background: #f3f4f6; }

.demo-date-range-picker__calendar { padding: 0.75rem; }

.demo-date-range-picker__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.75rem;
}

.demo-date-range-picker__header button {
  display: flex;
  padding: 0.25rem;
  background: none;
  border: none;
  cursor: pointer;
  border-radius: 0.25rem;
}

.demo-date-range-picker__header button:hover { background: #f3f4f6; }
.demo-date-range-picker__header svg { width: 1rem; height: 1rem; }
.demo-date-range-picker__header span { font-size: 0.875rem; font-weight: 600; }

.demo-date-range-picker__weekdays {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.25rem;
  margin-bottom: 0.25rem;
}

.demo-date-range-picker__weekdays span {
  font-size: 0.75rem;
  font-weight: 500;
  color: #6b7280;
  text-align: center;
  padding: 0.25rem;
}

.demo-date-range-picker__days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.125rem;
}

.demo-date-range-picker__day {
  width: 2rem;
  height: 2rem;
  font-size: 0.75rem;
  background: none;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
}

.demo-date-range-picker__day:hover { background: #f3f4f6; }
.demo-date-range-picker__day--other { color: #9ca3af; }
.demo-date-range-picker__day--start,
.demo-date-range-picker__day--end { background: #6366f1; color: white; }
.demo-date-range-picker__day--in-range { background: #e0e7ff; }
</style>
