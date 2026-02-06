<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { ClockIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: 'Sélectionner une heure'
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
    minuteStep: {
        type: Number,
        default: 5
    },
    minTime: {
        type: String,
        default: null
    },
    maxTime: {
        type: String,
        default: null
    },
    clearable: {
        type: Boolean,
        default: true
    },
    format24h: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'open', 'close'])

const containerRef = ref(null)
const isOpen = ref(false)
const hoursRef = ref(null)
const minutesRef = ref(null)

const hasError = computed(() => !!props.error)

const parsedValue = computed(() => {
    if (!props.modelValue) return { hours: null, minutes: null }
    const [h, m] = props.modelValue.split(':').map(Number)
    return { hours: h, minutes: m }
})

const displayValue = computed(() => {
    if (!props.modelValue) return ''
    const { hours, minutes } = parsedValue.value
    if (props.format24h) {
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`
    }
    const ampm = hours >= 12 ? 'PM' : 'AM'
    const h12 = hours % 12 || 12
    return `${h12}:${String(minutes).padStart(2, '0')} ${ampm}`
})

const hoursList = computed(() => {
    const maxH = props.format24h ? 23 : 23
    return Array.from({ length: maxH + 1 }, (_, i) => i)
})

const minutesList = computed(() => {
    const list = []
    for (let i = 0; i < 60; i += props.minuteStep) {
        list.push(i)
    }
    return list
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

function isTimeDisabled(h, m) {
    const time = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`
    if (props.minTime && time < props.minTime) return true
    if (props.maxTime && time > props.maxTime) return true
    return false
}

function selectHour(h) {
    const m = parsedValue.value.minutes ?? 0
    if (isTimeDisabled(h, m)) return
    const val = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`
    emit('update:modelValue', val)
    emit('change', val)
}

function selectMinute(m) {
    const h = parsedValue.value.hours ?? 0
    if (isTimeDisabled(h, m)) return
    const val = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`
    emit('update:modelValue', val)
    emit('change', val)
}

function open() {
    if (props.disabled) return
    isOpen.value = true
    emit('open')
    // Scroll to selected values
    setTimeout(() => {
        if (parsedValue.value.hours !== null && hoursRef.value) {
            const el = hoursRef.value.querySelector('[data-selected="true"]')
            if (el) el.scrollIntoView({ block: 'center' })
        }
        if (parsedValue.value.minutes !== null && minutesRef.value) {
            const el = minutesRef.value.querySelector('[data-selected="true"]')
            if (el) el.scrollIntoView({ block: 'center' })
        }
    }, 50)
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
                    ? 'border-red-300'
                    : 'border-gray-300',
                isOpen ? (hasError ? 'border-red-500 ring-2 ring-red-500/20' : 'border-primary-500 ring-2 ring-primary-500/20') : ''
            ]"
            tabindex="0"
            @click="toggle"
            @keydown.enter.prevent="toggle"
            @keydown.escape="close"
        >
            <ClockIcon :class="[iconSizes, 'mr-2 flex-shrink-0', hasError ? 'text-red-400' : 'text-gray-400']" />
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
                class="absolute z-50 mt-1 rounded-lg border border-gray-200 bg-white shadow-lg"
            >
                <div class="flex" style="height: 200px;">
                    <!-- Hours -->
                    <div
                        ref="hoursRef"
                        class="w-16 overflow-auto border-r border-gray-100 py-1"
                    >
                        <button
                            v-for="h in hoursList"
                            :key="h"
                            type="button"
                            :data-selected="parsedValue.hours === h"
                            :class="[
                                'w-full px-3 py-1.5 text-center text-sm transition-colors cursor-pointer',
                                parsedValue.hours === h
                                    ? 'bg-primary-500 text-white font-medium'
                                    : 'text-gray-700 hover:bg-gray-100'
                            ]"
                            @click="selectHour(h)"
                        >
                            {{ String(h).padStart(2, '0') }}
                        </button>
                    </div>

                    <!-- Minutes -->
                    <div
                        ref="minutesRef"
                        class="w-16 overflow-auto py-1"
                    >
                        <button
                            v-for="m in minutesList"
                            :key="m"
                            type="button"
                            :data-selected="parsedValue.minutes === m"
                            :class="[
                                'w-full px-3 py-1.5 text-center text-sm transition-colors cursor-pointer',
                                parsedValue.minutes === m
                                    ? 'bg-primary-500 text-white font-medium'
                                    : 'text-gray-700 hover:bg-gray-100'
                            ]"
                            @click="selectMinute(m)"
                        >
                            {{ String(m).padStart(2, '0') }}
                        </button>
                    </div>
                </div>

                <!-- Now button -->
                <div class="border-t border-gray-100 p-1">
                    <button
                        type="button"
                        class="w-full rounded py-1.5 text-xs font-medium text-primary-600 hover:bg-primary-50 cursor-pointer"
                        @click="() => {
                            const now = new Date()
                            const val = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`
                            emit('update:modelValue', val)
                            emit('change', val)
                            close()
                        }"
                    >
                        Maintenant
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
