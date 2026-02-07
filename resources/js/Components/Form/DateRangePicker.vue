<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, CalendarIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [null, null]
    },
    placeholder: {
        type: String,
        default: 'Sélectionner une période'
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
    clearable: {
        type: Boolean,
        default: true
    },
    firstDayOfWeek: {
        type: Number,
        default: 1
    },
    presets: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const containerRef = ref(null)
const isOpen = ref(false)
const leftMonth = ref(new Date())
const hoverDate = ref(null)
const selectingEnd = ref(false)

const hasError = computed(() => !!props.error)
const DAYS_FR = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di']
const MONTHS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']

const startDate = computed(() => props.modelValue[0] ? parseDate(props.modelValue[0]) : null)
const endDate = computed(() => props.modelValue[1] ? parseDate(props.modelValue[1]) : null)

const rightMonth = computed(() => new Date(leftMonth.value.getFullYear(), leftMonth.value.getMonth() + 1, 1))

const displayValue = computed(() => {
    if (!startDate.value) return ''
    const s = formatDate(startDate.value)
    if (!endDate.value) return s + ' - ...'
    return `${s} - ${formatDate(endDate.value)}`
})

const sizeClasses = computed(() => {
    const sizes = { sm: 'px-2.5 py-1.5 text-xs', md: 'px-3 py-2 text-sm', lg: 'px-4 py-2.5 text-base' }
    return sizes[props.size]
})

const iconSizes = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

function parseDate(str) {
    if (!str) return null
    const [y, m, d] = str.split('-').map(Number)
    return new Date(y, m - 1, d)
}

function formatDate(date) {
    const d = String(date.getDate()).padStart(2, '0')
    const m = String(date.getMonth() + 1).padStart(2, '0')
    return `${d}/${m}/${date.getFullYear()}`
}

function toISODate(date) {
    const d = String(date.getDate()).padStart(2, '0')
    const m = String(date.getMonth() + 1).padStart(2, '0')
    return `${date.getFullYear()}-${m}-${d}`
}

function sameDay(a, b) {
    if (!a || !b) return false
    return a.getDate() === b.getDate() && a.getMonth() === b.getMonth() && a.getFullYear() === b.getFullYear()
}

function isToday(date) {
    return sameDay(date, new Date())
}

function isInRange(date) {
    if (!startDate.value) return false
    const end = endDate.value || (hoverDate.value ? hoverDate.value : null)
    if (!end) return false
    return date > startDate.value && date < end
}

function isRangeStart(date) {
    return sameDay(date, startDate.value)
}

function isRangeEnd(date) {
    if (endDate.value) return sameDay(date, endDate.value)
    if (hoverDate.value && selectingEnd.value) return sameDay(date, hoverDate.value)
    return false
}

function isDisabledDate(date) {
    if (props.minDate && date < parseDate(props.minDate)) return true
    if (props.maxDate && date > parseDate(props.maxDate)) return true
    return false
}

function getCalendarDays(monthDate) {
    const year = monthDate.getFullYear()
    const month = monthDate.getMonth()
    const firstDay = new Date(year, month, 1)
    const lastDay = new Date(year, month + 1, 0)
    let startDay = firstDay.getDay() - props.firstDayOfWeek
    if (startDay < 0) startDay += 7
    const days = []
    const prevMonthLastDay = new Date(year, month, 0).getDate()
    for (let i = startDay - 1; i >= 0; i--) {
        days.push({ date: new Date(year, month - 1, prevMonthLastDay - i), currentMonth: false, day: prevMonthLastDay - i })
    }
    for (let d = 1; d <= lastDay.getDate(); d++) {
        days.push({ date: new Date(year, month, d), currentMonth: true, day: d })
    }
    const remaining = 42 - days.length
    for (let d = 1; d <= remaining; d++) {
        days.push({ date: new Date(year, month + 1, d), currentMonth: false, day: d })
    }
    return days
}

const leftDays = computed(() => getCalendarDays(leftMonth.value))
const rightDays = computed(() => getCalendarDays(rightMonth.value))

function selectDay(item) {
    if (isDisabledDate(item.date)) return

    if (!selectingEnd.value) {
        // Selecting start
        emit('update:modelValue', [toISODate(item.date), null])
        selectingEnd.value = true
    } else {
        // Selecting end
        let start = props.modelValue[0]
        let end = toISODate(item.date)
        if (end < start) {
            [start, end] = [end, start]
        }
        emit('update:modelValue', [start, end])
        emit('change', [start, end])
        selectingEnd.value = false
        close()
    }
}

function applyPreset(preset) {
    emit('update:modelValue', [preset.start, preset.end])
    emit('change', [preset.start, preset.end])
    selectingEnd.value = false
    close()
}

function open() {
    if (props.disabled) return
    isOpen.value = true
    selectingEnd.value = false
    if (startDate.value) {
        leftMonth.value = new Date(startDate.value.getFullYear(), startDate.value.getMonth(), 1)
    } else {
        leftMonth.value = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
    }
}

function close() { isOpen.value = false; hoverDate.value = null }
function toggle() { isOpen.value ? close() : open() }
function clear() { emit('update:modelValue', [null, null]); emit('change', [null, null]); selectingEnd.value = false }
function prevMonth() { leftMonth.value = new Date(leftMonth.value.getFullYear(), leftMonth.value.getMonth() - 1, 1) }
function nextMonth() { leftMonth.value = new Date(leftMonth.value.getFullYear(), leftMonth.value.getMonth() + 1, 1) }

function onClickOutside(e) {
    if (containerRef.value && !containerRef.value.contains(e.target)) close()
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
                hasError ? 'border-red-300' : 'border-gray-300',
                isOpen ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-indigo-500 ring-2 ring-indigo-500/20') : ''
            ]"
            tabindex="0"
            @click="toggle"
            @keydown.enter.prevent="toggle"
            @keydown.escape="close"
        >
            <CalendarIcon :class="[iconSizes, 'mr-2 flex-shrink-0', hasError ? 'text-red-400' : 'text-gray-400']" />
            <span :class="displayValue ? 'text-gray-900' : 'text-gray-400'" class="flex-1 truncate">
                {{ displayValue || placeholder }}
            </span>
            <button
                v-if="clearable && modelValue[0] && !disabled"
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
            <div v-if="isOpen" class="absolute z-50 mt-1 rounded-lg border border-gray-200 bg-white shadow-lg">
                <div class="flex">
                    <!-- Presets -->
                    <div v-if="presets.length" class="w-36 border-r border-gray-200 py-2">
                        <button
                            v-for="preset in presets"
                            :key="preset.label"
                            type="button"
                            class="w-full px-3 py-1.5 text-left text-xs text-gray-700 hover:bg-gray-50 cursor-pointer"
                            @click="applyPreset(preset)"
                        >
                            {{ preset.label }}
                        </button>
                    </div>

                    <!-- Calendars -->
                    <div class="flex">
                        <!-- Left calendar -->
                        <div class="p-3" style="width: 260px;">
                            <div class="mb-2 flex items-center justify-between">
                                <button type="button" class="rounded p-1 text-gray-500 hover:bg-gray-100 cursor-pointer" @click="prevMonth">
                                    <ChevronLeftIcon class="h-4 w-4" />
                                </button>
                                <span class="text-xs font-semibold text-gray-900">{{ MONTHS_FR[leftMonth.getMonth()] }} {{ leftMonth.getFullYear() }}</span>
                                <div class="w-6" />
                            </div>
                            <div class="mb-1 grid grid-cols-7 text-center">
                                <div v-for="day in DAYS_FR" :key="day" class="py-1 text-xs font-medium text-gray-500">{{ day }}</div>
                            </div>
                            <div class="grid grid-cols-7 text-center">
                                <button
                                    v-for="(item, i) in leftDays"
                                    :key="'l' + i"
                                    type="button"
                                    :class="[
                                        'py-1.5 text-xs transition-colors cursor-pointer',
                                        item.currentMonth ? 'text-gray-900' : 'text-gray-400',
                                        isToday(item.date) ? 'font-bold' : '',
                                        isRangeStart(item.date) ? 'bg-indigo-500 text-white font-semibold rounded-l-lg' : '',
                                        isRangeEnd(item.date) ? 'bg-indigo-500 text-white font-semibold rounded-r-lg' : '',
                                        isInRange(item.date) ? 'bg-indigo-50 text-indigo-700' : '',
                                        !isRangeStart(item.date) && !isRangeEnd(item.date) && !isInRange(item.date) ? 'hover:bg-gray-100 rounded-lg' : '',
                                        isDisabledDate(item.date) ? 'opacity-40 cursor-not-allowed' : ''
                                    ]"
                                    :disabled="isDisabledDate(item.date)"
                                    @click="selectDay(item)"
                                    @mouseenter="selectingEnd && (hoverDate = item.date)"
                                >
                                    {{ item.day }}
                                </button>
                            </div>
                        </div>

                        <!-- Right calendar -->
                        <div class="border-l border-gray-200 p-3" style="width: 260px;">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="w-6" />
                                <span class="text-xs font-semibold text-gray-900">{{ MONTHS_FR[rightMonth.getMonth()] }} {{ rightMonth.getFullYear() }}</span>
                                <button type="button" class="rounded p-1 text-gray-500 hover:bg-gray-100 cursor-pointer" @click="nextMonth">
                                    <ChevronRightIcon class="h-4 w-4" />
                                </button>
                            </div>
                            <div class="mb-1 grid grid-cols-7 text-center">
                                <div v-for="day in DAYS_FR" :key="day" class="py-1 text-xs font-medium text-gray-500">{{ day }}</div>
                            </div>
                            <div class="grid grid-cols-7 text-center">
                                <button
                                    v-for="(item, i) in rightDays"
                                    :key="'r' + i"
                                    type="button"
                                    :class="[
                                        'py-1.5 text-xs transition-colors cursor-pointer',
                                        item.currentMonth ? 'text-gray-900' : 'text-gray-400',
                                        isToday(item.date) ? 'font-bold' : '',
                                        isRangeStart(item.date) ? 'bg-indigo-500 text-white font-semibold rounded-l-lg' : '',
                                        isRangeEnd(item.date) ? 'bg-indigo-500 text-white font-semibold rounded-r-lg' : '',
                                        isInRange(item.date) ? 'bg-indigo-50 text-indigo-700' : '',
                                        !isRangeStart(item.date) && !isRangeEnd(item.date) && !isInRange(item.date) ? 'hover:bg-gray-100 rounded-lg' : '',
                                        isDisabledDate(item.date) ? 'opacity-40 cursor-not-allowed' : ''
                                    ]"
                                    :disabled="isDisabledDate(item.date)"
                                    @click="selectDay(item)"
                                    @mouseenter="selectingEnd && (hoverDate = item.date)"
                                >
                                    {{ item.day }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
