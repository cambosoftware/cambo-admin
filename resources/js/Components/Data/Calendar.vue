<script setup>
import { ref, computed, watch } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, PlusIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Array of events
     * Each event: { id, title, date (YYYY-MM-DD), endDate?, color?, allDay? }
     */
    events: {
        type: Array,
        default: () => []
    },
    /**
     * Initial date to display (defaults to today)
     */
    initialDate: {
        type: [Date, String],
        default: () => new Date()
    },
    /**
     * First day of week (0 = Sunday, 1 = Monday)
     */
    weekStartsOn: {
        type: Number,
        default: 1
    },
    /**
     * Locale for date formatting
     */
    locale: {
        type: String,
        default: 'fr-FR'
    },
    /**
     * Allow adding events
     */
    allowAdd: {
        type: Boolean,
        default: true
    },
    /**
     * Show week numbers
     */
    showWeekNumbers: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['date-click', 'event-click', 'month-change', 'add-event'])

// Current displayed month
const currentDate = ref(new Date(props.initialDate))

// Day names based on locale
const dayNames = computed(() => {
    const days = []
    const date = new Date(2024, 0, props.weekStartsOn) // Start from first day
    for (let i = 0; i < 7; i++) {
        days.push(new Intl.DateTimeFormat(props.locale, { weekday: 'short' }).format(date))
        date.setDate(date.getDate() + 1)
    }
    return days
})

// Month name
const monthName = computed(() => {
    return new Intl.DateTimeFormat(props.locale, { month: 'long', year: 'numeric' }).format(currentDate.value)
})

// Generate calendar days
const calendarDays = computed(() => {
    const year = currentDate.value.getFullYear()
    const month = currentDate.value.getMonth()

    // First day of month
    const firstDay = new Date(year, month, 1)
    // Last day of month
    const lastDay = new Date(year, month + 1, 0)

    // Start from the correct weekday
    const startDate = new Date(firstDay)
    const dayOfWeek = startDate.getDay()
    const diff = (dayOfWeek - props.weekStartsOn + 7) % 7
    startDate.setDate(startDate.getDate() - diff)

    // Generate 6 weeks
    const days = []
    const current = new Date(startDate)

    for (let week = 0; week < 6; week++) {
        const weekDays = []
        for (let day = 0; day < 7; day++) {
            const dateStr = formatDate(current)
            const dayEvents = props.events.filter(e => {
                const eventDate = e.date
                const eventEndDate = e.endDate || e.date
                return dateStr >= eventDate && dateStr <= eventEndDate
            })

            weekDays.push({
                date: new Date(current),
                dateStr,
                day: current.getDate(),
                isCurrentMonth: current.getMonth() === month,
                isToday: isToday(current),
                isWeekend: current.getDay() === 0 || current.getDay() === 6,
                events: dayEvents
            })
            current.setDate(current.getDate() + 1)
        }
        days.push({
            weekNumber: getWeekNumber(weekDays[0].date),
            days: weekDays
        })

        // Stop if we've passed the month
        if (current.getMonth() !== month && current.getDate() > 7) break
    }

    return days
})

// Helpers
const formatDate = (date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

const isToday = (date) => {
    const today = new Date()
    return date.getDate() === today.getDate() &&
           date.getMonth() === today.getMonth() &&
           date.getFullYear() === today.getFullYear()
}

const getWeekNumber = (date) => {
    const d = new Date(date)
    d.setHours(0, 0, 0, 0)
    d.setDate(d.getDate() + 4 - (d.getDay() || 7))
    const yearStart = new Date(d.getFullYear(), 0, 1)
    return Math.ceil((((d - yearStart) / 86400000) + 1) / 7)
}

// Event colors
const eventColors = {
    default: 'bg-primary-500',
    red: 'bg-red-500',
    orange: 'bg-orange-500',
    amber: 'bg-amber-500',
    green: 'bg-emerald-500',
    teal: 'bg-teal-500',
    blue: 'bg-blue-500',
    indigo: 'bg-indigo-500',
    purple: 'bg-purple-500',
    pink: 'bg-pink-500'
}

const getEventColor = (event) => {
    if (event.color?.startsWith('#') || event.color?.startsWith('rgb')) {
        return ''
    }
    return eventColors[event.color] || eventColors.default
}

const getEventStyle = (event) => {
    if (event.color?.startsWith('#') || event.color?.startsWith('rgb')) {
        return { backgroundColor: event.color }
    }
    return {}
}

// Navigation
const previousMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
    emit('month-change', { year: currentDate.value.getFullYear(), month: currentDate.value.getMonth() })
}

const nextMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
    emit('month-change', { year: currentDate.value.getFullYear(), month: currentDate.value.getMonth() })
}

const goToToday = () => {
    currentDate.value = new Date()
    emit('month-change', { year: currentDate.value.getFullYear(), month: currentDate.value.getMonth() })
}

// Event handlers
const onDateClick = (day) => {
    emit('date-click', { date: day.date, dateStr: day.dateStr, events: day.events })
}

const onEventClick = (event, day, e) => {
    e.stopPropagation()
    emit('event-click', { event, date: day.date })
}

const onAddEvent = (day) => {
    emit('add-event', { date: day.date, dateStr: day.dateStr })
}
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                    @click="previousMonth"
                >
                    <ChevronLeftIcon class="h-5 w-5" />
                </button>
                <button
                    type="button"
                    class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                    @click="nextMonth"
                >
                    <ChevronRightIcon class="h-5 w-5" />
                </button>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white capitalize ml-2">
                    {{ monthName }}
                </h2>
            </div>
            <button
                type="button"
                class="px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
                @click="goToToday"
            >
                Aujourd'hui
            </button>
        </div>

        <!-- Calendar grid -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <!-- Day names -->
                <thead>
                    <tr>
                        <th
                            v-if="showWeekNumbers"
                            class="w-10 py-2 text-xs font-medium text-gray-400 dark:text-gray-500 text-center"
                        >
                            S
                        </th>
                        <th
                            v-for="day in dayNames"
                            :key="day"
                            class="py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-center"
                        >
                            {{ day }}
                        </th>
                    </tr>
                </thead>

                <!-- Calendar days -->
                <tbody>
                    <tr v-for="week in calendarDays" :key="week.weekNumber">
                        <!-- Week number -->
                        <td
                            v-if="showWeekNumbers"
                            class="py-1 text-xs text-gray-400 dark:text-gray-500 text-center border-r border-gray-100 dark:border-gray-700"
                        >
                            {{ week.weekNumber }}
                        </td>

                        <!-- Days -->
                        <td
                            v-for="day in week.days"
                            :key="day.dateStr"
                            :class="[
                                'relative h-24 sm:h-28 p-1 border border-gray-100 dark:border-gray-700 align-top cursor-pointer transition-colors',
                                day.isCurrentMonth
                                    ? 'bg-white dark:bg-gray-800'
                                    : 'bg-gray-50 dark:bg-gray-800/50',
                                'hover:bg-gray-50 dark:hover:bg-gray-700/50'
                            ]"
                            @click="onDateClick(day)"
                        >
                            <!-- Day number -->
                            <div class="flex items-center justify-between">
                                <span
                                    :class="[
                                        'inline-flex items-center justify-center w-6 h-6 text-sm rounded-full',
                                        day.isToday
                                            ? 'bg-primary-600 text-white font-semibold'
                                            : day.isCurrentMonth
                                                ? 'text-gray-900 dark:text-white'
                                                : 'text-gray-400 dark:text-gray-500'
                                    ]"
                                >
                                    {{ day.day }}
                                </span>

                                <!-- Add button -->
                                <button
                                    v-if="allowAdd"
                                    type="button"
                                    class="p-0.5 text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400 rounded opacity-0 hover:opacity-100 focus:opacity-100"
                                    @click.stop="onAddEvent(day)"
                                >
                                    <PlusIcon class="h-4 w-4" />
                                </button>
                            </div>

                            <!-- Events -->
                            <div class="mt-1 space-y-0.5 overflow-hidden">
                                <template v-for="(event, index) in day.events.slice(0, 3)" :key="event.id">
                                    <div
                                        :class="[
                                            'px-1.5 py-0.5 text-xs text-white rounded truncate cursor-pointer hover:opacity-80',
                                            getEventColor(event)
                                        ]"
                                        :style="getEventStyle(event)"
                                        :title="event.title"
                                        @click="(e) => onEventClick(event, day, e)"
                                    >
                                        {{ event.title }}
                                    </div>
                                </template>

                                <!-- More events indicator -->
                                <div
                                    v-if="day.events.length > 3"
                                    class="px-1.5 text-xs text-gray-500 dark:text-gray-400"
                                >
                                    +{{ day.events.length - 3 }} autres
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
