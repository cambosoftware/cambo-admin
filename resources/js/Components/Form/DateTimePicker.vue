<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, CalendarIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: 'Sélectionner date et heure'
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
    minuteStep: {
        type: Number,
        default: 5
    },
    clearable: {
        type: Boolean,
        default: true
    },
    firstDayOfWeek: {
        type: Number,
        default: 1
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const containerRef = ref(null)
const isOpen = ref(false)
const viewDate = ref(new Date())
const selectedHour = ref(0)
const selectedMinute = ref(0)

const hasError = computed(() => !!props.error)
const DAYS_FR = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di']
const MONTHS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']

const parsedValue = computed(() => {
    if (!props.modelValue) return null
    // Expected format: YYYY-MM-DD HH:mm or YYYY-MM-DDTHH:mm
    const [datePart, timePart] = props.modelValue.replace('T', ' ').split(' ')
    const [y, m, d] = datePart.split('-').map(Number)
    const [h, min] = timePart ? timePart.split(':').map(Number) : [0, 0]
    return { date: new Date(y, m - 1, d), hours: h, minutes: min }
})

const displayValue = computed(() => {
    if (!parsedValue.value) return ''
    const d = parsedValue.value.date
    const dd = String(d.getDate()).padStart(2, '0')
    const mm = String(d.getMonth() + 1).padStart(2, '0')
    const hh = String(parsedValue.value.hours).padStart(2, '0')
    const min = String(parsedValue.value.minutes).padStart(2, '0')
    return `${dd}/${mm}/${d.getFullYear()} ${hh}:${min}`
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
})

const minutesList = computed(() => {
    const list = []
    for (let i = 0; i < 60; i += props.minuteStep) list.push(i)
    return list
})

const sizeClasses = computed(() => {
    const sizes = { sm: 'px-2.5 py-1.5 text-xs', md: 'px-3 py-2 text-sm', lg: 'px-4 py-2.5 text-base' }
    return sizes[props.size]
})

const iconSizes = computed(() => {
    const sizes = { sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' }
    return sizes[props.size]
})

function toISODate(date) {
    const d = String(date.getDate()).padStart(2, '0')
    const m = String(date.getMonth() + 1).padStart(2, '0')
    return `${date.getFullYear()}-${m}-${d}`
}

function isToday(date) {
    const today = new Date()
    return date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear()
}

function isSelectedDate(date) {
    if (!parsedValue.value) return false
    const sel = parsedValue.value.date
    return date.getDate() === sel.getDate() && date.getMonth() === sel.getMonth() && date.getFullYear() === sel.getFullYear()
}

function buildValue(date, h, m) {
    return `${toISODate(date)} ${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`
}

function selectDay(item) {
    const val = buildValue(item.date, selectedHour.value, selectedMinute.value)
    emit('update:modelValue', val)
    emit('change', val)
}

function setHour(h) {
    selectedHour.value = h
    if (parsedValue.value) {
        const val = buildValue(parsedValue.value.date, h, selectedMinute.value)
        emit('update:modelValue', val)
        emit('change', val)
    }
}

function setMinute(m) {
    selectedMinute.value = m
    if (parsedValue.value) {
        const val = buildValue(parsedValue.value.date, selectedHour.value, m)
        emit('update:modelValue', val)
        emit('change', val)
    }
}

function open() {
    if (props.disabled) return
    isOpen.value = true
    if (parsedValue.value) {
        viewDate.value = new Date(parsedValue.value.date)
        selectedHour.value = parsedValue.value.hours
        selectedMinute.value = parsedValue.value.minutes
    } else {
        viewDate.value = new Date()
        selectedHour.value = new Date().getHours()
        selectedMinute.value = 0
    }
}

function close() { isOpen.value = false }
function toggle() { isOpen.value ? close() : open() }
function clear() { emit('update:modelValue', null); emit('change', null) }
function prevMonth() { viewDate.value = new Date(viewDate.value.getFullYear(), viewDate.value.getMonth() - 1, 1) }
function nextMonth() { viewDate.value = new Date(viewDate.value.getFullYear(), viewDate.value.getMonth() + 1, 1) }

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
                isOpen ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-primary-500 ring-2 ring-primary-500/20') : ''
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
            <div v-if="isOpen" class="absolute z-50 mt-1 rounded-lg border border-gray-200 bg-white shadow-lg">
                <div class="flex">
                    <!-- Calendar -->
                    <div class="p-3" style="width: 280px;">
                        <!-- Header -->
                        <div class="mb-2 flex items-center justify-between">
                            <button type="button" class="rounded p-1 text-gray-500 hover:bg-gray-100 cursor-pointer" @click="prevMonth">
                                <ChevronLeftIcon class="h-4 w-4" />
                            </button>
                            <span class="text-sm font-semibold text-gray-900">{{ currentMonth }} {{ currentYear }}</span>
                            <button type="button" class="rounded p-1 text-gray-500 hover:bg-gray-100 cursor-pointer" @click="nextMonth">
                                <ChevronRightIcon class="h-4 w-4" />
                            </button>
                        </div>

                        <!-- Weekdays -->
                        <div class="mb-1 grid grid-cols-7 text-center">
                            <div v-for="day in DAYS_FR" :key="day" class="py-1 text-xs font-medium text-gray-500">{{ day }}</div>
                        </div>

                        <!-- Days -->
                        <div class="grid grid-cols-7 text-center">
                            <button
                                v-for="(item, i) in calendarDays"
                                :key="i"
                                type="button"
                                :class="[
                                    'rounded-lg py-1.5 text-xs transition-colors cursor-pointer',
                                    item.currentMonth ? 'text-gray-900' : 'text-gray-400',
                                    isToday(item.date) && !isSelectedDate(item.date) ? 'font-bold text-primary-600' : '',
                                    isSelectedDate(item.date) ? 'bg-primary-500 text-white font-semibold' : 'hover:bg-gray-100'
                                ]"
                                @click="selectDay(item)"
                            >
                                {{ item.day }}
                            </button>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="flex border-l border-gray-200" style="height: 300px;">
                        <!-- Hours -->
                        <div class="w-14 overflow-auto border-r border-gray-100 py-1">
                            <div class="px-2 py-1 text-xs font-medium text-gray-500 text-center">H</div>
                            <button
                                v-for="h in 24"
                                :key="h - 1"
                                type="button"
                                :class="[
                                    'w-full px-2 py-1 text-center text-xs transition-colors cursor-pointer',
                                    selectedHour === h - 1 ? 'bg-primary-500 text-white font-medium' : 'text-gray-700 hover:bg-gray-100'
                                ]"
                                @click="setHour(h - 1)"
                            >
                                {{ String(h - 1).padStart(2, '0') }}
                            </button>
                        </div>
                        <!-- Minutes -->
                        <div class="w-14 overflow-auto py-1">
                            <div class="px-2 py-1 text-xs font-medium text-gray-500 text-center">M</div>
                            <button
                                v-for="m in minutesList"
                                :key="m"
                                type="button"
                                :class="[
                                    'w-full px-2 py-1 text-center text-xs transition-colors cursor-pointer',
                                    selectedMinute === m ? 'bg-primary-500 text-white font-medium' : 'text-gray-700 hover:bg-gray-100'
                                ]"
                                @click="setMinute(m)"
                            >
                                {{ String(m).padStart(2, '0') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between border-t border-gray-100 p-2">
                    <button
                        type="button"
                        class="rounded px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 cursor-pointer"
                        @click="() => {
                            const now = new Date()
                            const val = buildValue(now, now.getHours(), now.getMinutes())
                            emit('update:modelValue', val)
                            emit('change', val)
                            close()
                        }"
                    >
                        Maintenant
                    </button>
                    <button
                        type="button"
                        class="rounded bg-primary-500 px-3 py-1 text-xs font-medium text-white hover:bg-primary-600 cursor-pointer"
                        @click="close"
                    >
                        OK
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
