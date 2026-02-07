<template>
  <div class="demo-datetime-showcase">
    <span class="demo-label">Select date and time:</span>
    <div class="demo-datetime-picker">
      <div class="demo-datetime-picker__input-wrapper" @click="isOpen = !isOpen">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span :class="['demo-datetime-picker__input-text', { 'demo-datetime-picker__input-text--placeholder': !formattedDateTime }]">
          {{ formattedDateTime || 'Select date and time...' }}
        </span>
      </div>
      <div v-if="isOpen" class="demo-datetime-picker__dropdown">
        <div class="demo-datetime-picker__tabs">
          <button
            :class="['demo-datetime-picker__tab', { 'demo-datetime-picker__tab--active': activeTab === 'date' }]"
            @click="activeTab = 'date'"
          >
            Date
          </button>
          <button
            :class="['demo-datetime-picker__tab', { 'demo-datetime-picker__tab--active': activeTab === 'time' }]"
            @click="activeTab = 'time'"
          >
            Time
          </button>
        </div>

        <!-- Date Panel -->
        <div v-if="activeTab === 'date'" class="demo-datetime-picker__panel">
          <div class="demo-datetime-picker__header">
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
          <div class="demo-datetime-picker__weekdays">
            <span v-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']" :key="day">{{ day }}</span>
          </div>
          <div class="demo-datetime-picker__days">
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

        <!-- Time Panel -->
        <div v-else class="demo-datetime-picker__panel demo-datetime-picker__time-panel">
          <div class="demo-datetime-picker__time-columns">
            <div class="demo-datetime-picker__time-column">
              <div class="demo-datetime-picker__time-label">Hour</div>
              <div class="demo-datetime-picker__time-options">
                <button
                  v-for="h in 24"
                  :key="h"
                  :class="['demo-datetime-picker__time-option', { 'demo-datetime-picker__time-option--selected': selectedHour === h - 1 }]"
                  @click="selectedHour = h - 1"
                >
                  {{ (h - 1).toString().padStart(2, '0') }}
                </button>
              </div>
            </div>
            <div class="demo-datetime-picker__time-column">
              <div class="demo-datetime-picker__time-label">Minute</div>
              <div class="demo-datetime-picker__time-options">
                <button
                  v-for="m in 12"
                  :key="m"
                  :class="['demo-datetime-picker__time-option', { 'demo-datetime-picker__time-option--selected': selectedMinute === (m - 1) * 5 }]"
                  @click="selectedMinute = (m - 1) * 5"
                >
                  {{ ((m - 1) * 5).toString().padStart(2, '0') }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="demo-datetime-picker__actions">
          <button @click="isOpen = false">Cancel</button>
          <button class="demo-datetime-picker__action--primary" @click="confirm">OK</button>
        </div>
      </div>
    </div>
    <span class="demo-selected">Selected: {{ formattedDateTime || 'No date/time selected' }}</span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const isOpen = ref(false)
const activeTab = ref('date')
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())

// Pre-selected date/time (today at 2:30 PM)
const today = new Date()
const selectedDate = ref(today.toISOString().split('T')[0])
const selectedHour = ref(14)
const selectedMinute = ref(30)
const confirmedDateTime = ref(`${selectedDate.value}T${selectedHour.value.toString().padStart(2, '0')}:${selectedMinute.value.toString().padStart(2, '0')}:00`)

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
const monthYear = computed(() => `${months[currentMonth.value]} ${currentYear.value}`)

const formattedDateTime = computed(() => {
  if (!confirmedDateTime.value) return ''
  const date = new Date(confirmedDateTime.value)
  return date.toLocaleString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
})

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
  const classes = ['demo-datetime-picker__day']
  if (day.otherMonth) classes.push('demo-datetime-picker__day--other')
  const dateStr = new Date(day.year, day.month, day.date).toISOString().split('T')[0]
  if (dateStr === selectedDate.value) classes.push('demo-datetime-picker__day--selected')
  return classes
}

const selectDate = (day) => {
  selectedDate.value = new Date(day.year, day.month, day.date).toISOString().split('T')[0]
  activeTab.value = 'time'
}

const confirm = () => {
  if (selectedDate.value) {
    confirmedDateTime.value = `${selectedDate.value}T${selectedHour.value.toString().padStart(2, '0')}:${selectedMinute.value.toString().padStart(2, '0')}:00`
  }
  isOpen.value = false
}
</script>

<style scoped>
.demo-datetime-showcase { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
.demo-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
.demo-selected { font-size: 0.75rem; color: #6b7280; }

.demo-datetime-picker { position: relative; width: fit-content; }

.demo-datetime-picker__input-wrapper {
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

.demo-datetime-picker__input-wrapper:hover { border-color: #9ca3af; }
.demo-datetime-picker__input-wrapper svg { width: 1.25rem; height: 1.25rem; color: #6b7280; }
.demo-datetime-picker__input-text { font-size: 0.875rem; min-width: 10rem; }
.demo-datetime-picker__input-text--placeholder { color: #9ca3af; }

.demo-datetime-picker__dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  margin-top: 0.25rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  z-index: 50;
  min-width: 16rem;
}

.demo-datetime-picker__tabs { display: flex; border-bottom: 1px solid #e5e7eb; }
.demo-datetime-picker__tab { flex: 1; padding: 0.75rem; font-size: 0.875rem; font-weight: 500; background: none; border: none; cursor: pointer; color: #6b7280; }
.demo-datetime-picker__tab--active { color: #6366f1; border-bottom: 2px solid #6366f1; margin-bottom: -1px; }

.demo-datetime-picker__panel { padding: 0.75rem; }
.demo-datetime-picker__header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
.demo-datetime-picker__header button { display: flex; padding: 0.25rem; background: none; border: none; cursor: pointer; border-radius: 0.25rem; }
.demo-datetime-picker__header button:hover { background: #f3f4f6; }
.demo-datetime-picker__header svg { width: 1rem; height: 1rem; }
.demo-datetime-picker__header span { font-size: 0.875rem; font-weight: 600; }

.demo-datetime-picker__weekdays { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.25rem; margin-bottom: 0.25rem; }
.demo-datetime-picker__weekdays span { font-size: 0.75rem; font-weight: 500; color: #6b7280; text-align: center; padding: 0.25rem; }

.demo-datetime-picker__days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.125rem; }
.demo-datetime-picker__day { width: 2rem; height: 2rem; font-size: 0.75rem; background: none; border: none; border-radius: 0.25rem; cursor: pointer; }
.demo-datetime-picker__day:hover { background: #f3f4f6; }
.demo-datetime-picker__day--other { color: #9ca3af; }
.demo-datetime-picker__day--selected { background: #6366f1; color: white; }

.demo-datetime-picker__time-panel { min-height: 12rem; }
.demo-datetime-picker__time-columns { display: flex; gap: 1rem; }
.demo-datetime-picker__time-column { flex: 1; }
.demo-datetime-picker__time-label { font-size: 0.75rem; font-weight: 500; color: #6b7280; text-align: center; margin-bottom: 0.5rem; }
.demo-datetime-picker__time-options { height: 10rem; overflow-y: auto; }
.demo-datetime-picker__time-option { display: block; width: 100%; padding: 0.375rem; font-size: 0.875rem; text-align: center; background: none; border: none; border-radius: 0.25rem; cursor: pointer; }
.demo-datetime-picker__time-option:hover { background: #f3f4f6; }
.demo-datetime-picker__time-option--selected { background: #6366f1; color: white; }

.demo-datetime-picker__actions { display: flex; justify-content: flex-end; gap: 0.5rem; padding: 0.5rem 0.75rem; border-top: 1px solid #e5e7eb; }
.demo-datetime-picker__actions button { padding: 0.375rem 0.75rem; font-size: 0.875rem; background: none; border: none; border-radius: 0.25rem; cursor: pointer; }
.demo-datetime-picker__actions button:hover { background: #f3f4f6; }
.demo-datetime-picker__action--primary { background: #6366f1 !important; color: white; }
.demo-datetime-picker__action--primary:hover { background: #4f46e5 !important; }
</style>
