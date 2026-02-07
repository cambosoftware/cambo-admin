<script setup>
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, CalendarIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: 'Sélectionner une date'
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    minDate: {
        type: String,
        default: null
    },
    maxDate: {
        type: String,
        default: null
    },
    format: {
        type: String,
        default: 'dd/MM/yyyy'
    },
    clearable: {
        type: Boolean,
        default: true
    },
    firstDayOfWeek: {
        type: Number,
        default: 1 // Monday
    },
    locale: {
        type: String,
        default: 'fr-FR'
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'open', 'close'])

const containerRef = ref(null)
const isOpen = ref(false)
const viewDate = ref(new Date())
const view = ref('days') // days, months, years

const hasError = computed(() => !!props.error)

const DAYS_FR = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di']
const MONTHS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']

const selectedDate = computed(() => {
    if (!props.modelValue) return null
    return parseDate(props.modelValue)
})

const displayValue = computed(() => {
    if (!selectedDate.value) return ''
    return formatDate(selectedDate.value)
})

const currentMonth = computed(() => MONTHS_FR[viewDate.value.getMonth()])
const currentYear = computed(() => viewDate.value.getFullYear())

const calendarDays = computed(() => {
    const year = viewDate.value.getFullYear()
    const month = viewDate.value.getMonth()

    const firstDay = new Date(year, month, 1)
    const lastDay = new Date(year, month + 1, 0)

    let startDay = firstDay.getDay() - props.firstDayOfWeek
    if (startDay < 0) startDay += 7

    const days = []

    // Previous month days
    const prevMonthLastDay = new Date(year, month, 0).getDate()
    for (let i = startDay - 1; i >= 0; i--) {
        days.push({
            date: new Date(year, month - 1, prevMonthLastDay - i),
            currentMonth: false,
            day: prevMonthLastDay - i
        })
    }

    // Current month days
    for (let d = 1; d <= lastDay.getDate(); d++) {
        days.push({
            date: new Date(year, month, d),
            currentMonth: true,
            day: d
        })
    }

    // Next month days to fill grid
    const remaining = 42 - days.length
    for (let d = 1; d <= remaining; d++) {
        days.push({
            date: new Date(year, month + 1, d),
            currentMonth: false,
            day: d
        })
    }

    return days
})

const yearRange = computed(() => {
    const baseYear = Math.floor(currentYear.value / 12) * 12
    return Array.from({ length: 12 }, (_, i) => baseYear + i)
})

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1.5 text-xs',
        md: 'px-3 py-2 text-sm',
        lg: 'px-4 py-2.5 text-base'
    }
    return sizes[props.size]
})

const iconSizes = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

function parseDate(str) {
    if (!str) return null
    // Support YYYY-MM-DD format
    const parts = str.split('-')
    if (parts.length === 3) {
        return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]))
    }
    return new Date(str)
}

function formatDate(date) {
    const d = String(date.getDate()).padStart(2, '0')
    const m = String(date.getMonth() + 1).padStart(2, '0')
    const y = date.getFullYear()

    return props.format
        .replace('dd', d)
        .replace('MM', m)
        .replace('yyyy', y)
}

function toISODate(date) {
    const d = String(date.getDate()).padStart(2, '0')
    const m = String(date.getMonth() + 1).padStart(2, '0')
    return `${date.getFullYear()}-${m}-${d}`
}

function isToday(date) {
    const today = new Date()
    return date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear()
}

function isSelected(date) {
    if (!selectedDate.value) return false
    return date.getDate() === selectedDate.value.getDate() &&
        date.getMonth() === selectedDate.value.getMonth() &&
        date.getFullYear() === selectedDate.value.getFullYear()
}

function isDisabledDate(date) {
    if (props.minDate && date < parseDate(props.minDate)) return true
    if (props.maxDate && date > parseDate(props.maxDate)) return true
    return false
}

function selectDay(item) {
    if (isDisabledDate(item.date)) return
    const val = toISODate(item.date)
    emit('update:modelValue', val)
    emit('change', val)
    close()
}

function selectMonth(month) {
    viewDate.value = new Date(viewDate.value.getFullYear(), month, 1)
    view.value = 'days'
}

function selectYear(year) {
    viewDate.value = new Date(year, viewDate.value.getMonth(), 1)
    view.value = 'months'
}

function prevMonth() {
    if (view.value === 'years') {
        viewDate.value = new Date(viewDate.value.getFullYear() - 12, viewDate.value.getMonth(), 1)
    } else {
        viewDate.value = new Date(viewDate.value.getFullYear(), viewDate.value.getMonth() - 1, 1)
    }
}

function nextMonth() {
    if (view.value === 'years') {
        viewDate.value = new Date(viewDate.value.getFullYear() + 12, viewDate.value.getMonth(), 1)
    } else {
        viewDate.value = new Date(viewDate.value.getFullYear(), viewDate.value.getMonth() + 1, 1)
    }
}

function open() {
    if (props.disabled) return
    isOpen.value = true
    view.value = 'days'
    if (selectedDate.value) {
        viewDate.value = new Date(selectedDate.value)
    } else {
        viewDate.value = new Date()
    }
    emit('open')
}

function close() {
    isOpen.value = false
    emit('close')
}

function toggle() {
    isOpen.value ? close() : open()
}

function clear() {
    emit('update:modelValue', null)
    emit('change', null)
}

function onClickOutside(e) {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        close()
    }
}

onMounted(() => document.addEventListener('mousedown', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('mousedown', onClickOutside))
</script>

<template>
    <div ref="containerRef" class="relative">
        <!-- Trigger -->
        <div
            :class="[
                'flex items-center rounded-lg border bg-white transition-colors duration-150 cursor-pointer',
                sizeClasses,
                disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '',
                hasError
                    ? 'border-red-300 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20'
                    : 'border-gray-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20',
                isOpen ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-indigo-500 ring-2 ring-indigo-500/20') : ''
            ]"
            tabindex="0"
            @click="toggle"
            @keydown.enter.prevent="toggle"
            @keydown.escape="close"
        >
            <CalendarIcon :class="[iconSizes, 'mr-2 flex-shrink-0', hasError ? 'text-red-400' : 'text-gray-400']" />
            <span :class="displayValue ? 'text-gray-900' : 'text-gray-400'" class="flex-1">
                {{ displayValue || placeholder }}
            </span>
            <button
                v-if="clearable && modelValue && !disabled"
                type="button"
                class="ml-1 text-gray-400 hover:text-gray-600 cursor-pointer"
                tabindex="-1"
                @click.stop="clear"
            >
                <XMarkIcon :class="iconSizes" />
            </button>
        </div>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute z-50 mt-1 w-72 rounded-lg border border-gray-200 bg-white p-3 shadow-lg"
            >
                <!-- Header -->
                <div class="mb-2 flex items-center justify-between">
                    <button
                        type="button"
                        class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700 cursor-pointer"
                        @click="prevMonth"
                    >
                        <ChevronLeftIcon class="h-4 w-4" />
                    </button>
                    <button
                        type="button"
                        class="text-sm font-semibold text-gray-900 hover:text-indigo-600 cursor-pointer"
                        @click="view = view === 'days' ? 'months' : view === 'months' ? 'years' : 'days'"
                    >
                        <template v-if="view === 'years'">
                            {{ yearRange[0] }} - {{ yearRange[yearRange.length - 1] }}
                        </template>
                        <template v-else-if="view === 'months'">
                            {{ currentYear }}
                        </template>
                        <template v-else>
                            {{ currentMonth }} {{ currentYear }}
                        </template>
                    </button>
                    <button
                        type="button"
                        class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700 cursor-pointer"
                        @click="nextMonth"
                    >
                        <ChevronRightIcon class="h-4 w-4" />
                    </button>
                </div>

                <!-- Days view -->
                <template v-if="view === 'days'">
                    <!-- Weekday headers -->
                    <div class="mb-1 grid grid-cols-7 text-center">
                        <div v-for="day in DAYS_FR" :key="day" class="py-1 text-xs font-medium text-gray-500">
                            {{ day }}
                        </div>
                    </div>

                    <!-- Days grid -->
                    <div class="grid grid-cols-7 text-center">
                        <button
                            v-for="(item, i) in calendarDays"
                            :key="i"
                            type="button"
                            :class="[
                                'rounded-lg py-1.5 text-xs transition-colors cursor-pointer',
                                item.currentMonth ? 'text-gray-900' : 'text-gray-400',
                                isToday(item.date) && !isSelected(item.date) ? 'font-bold text-indigo-600' : '',
                                isSelected(item.date) ? 'bg-indigo-500 text-white font-semibold' : 'hover:bg-gray-100',
                                isDisabledDate(item.date) ? 'opacity-40 cursor-not-allowed hover:bg-transparent' : ''
                            ]"
                            :disabled="isDisabledDate(item.date)"
                            @click="selectDay(item)"
                        >
                            {{ item.day }}
                        </button>
                    </div>
                </template>

                <!-- Months view -->
                <template v-else-if="view === 'months'">
                    <div class="grid grid-cols-3 gap-1">
                        <button
                            v-for="(month, i) in MONTHS_FR"
                            :key="i"
                            type="button"
                            :class="[
                                'rounded-lg px-2 py-2.5 text-xs transition-colors cursor-pointer',
                                selectedDate && selectedDate.getMonth() === i && selectedDate.getFullYear() === currentYear
                                    ? 'bg-indigo-500 text-white font-semibold'
                                    : 'text-gray-700 hover:bg-gray-100'
                            ]"
                            @click="selectMonth(i)"
                        >
                            {{ month.substring(0, 3) }}
                        </button>
                    </div>
                </template>

                <!-- Years view -->
                <template v-else>
                    <div class="grid grid-cols-3 gap-1">
                        <button
                            v-for="year in yearRange"
                            :key="year"
                            type="button"
                            :class="[
                                'rounded-lg px-2 py-2.5 text-xs transition-colors cursor-pointer',
                                selectedDate && selectedDate.getFullYear() === year
                                    ? 'bg-indigo-500 text-white font-semibold'
                                    : 'text-gray-700 hover:bg-gray-100'
                            ]"
                            @click="selectYear(year)"
                        >
                            {{ year }}
                        </button>
                    </div>
                </template>

                <!-- Today button -->
                <div class="mt-2 border-t border-gray-100 pt-2">
                    <button
                        type="button"
                        class="w-full rounded-lg py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 cursor-pointer"
                        @click="selectDay({ date: new Date() })"
                    >
                        Aujourd'hui
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
