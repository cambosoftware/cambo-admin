<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    text: {
        type: String,
        default: ''
    },
    position: {
        type: String,
        default: 'top',
        validator: (v) => ['top', 'bottom', 'left', 'right'].includes(v)
    },
    delay: {
        type: Number,
        default: 200
    }
})

const visible = ref(false)
let timeout = null

const show = () => {
    timeout = setTimeout(() => {
        visible.value = true
    }, props.delay)
}

const hide = () => {
    clearTimeout(timeout)
    visible.value = false
}

const positionClasses = computed(() => {
    const positions = {
        top: 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
        left: 'right-full top-1/2 -translate-y-1/2 mr-2',
        right: 'left-full top-1/2 -translate-y-1/2 ml-2'
    }
    return positions[props.position]
})

const arrowClasses = computed(() => {
    const arrows = {
        top: 'top-full left-1/2 -translate-x-1/2 border-t-gray-900 border-x-transparent border-b-transparent border-4',
        bottom: 'bottom-full left-1/2 -translate-x-1/2 border-b-gray-900 border-x-transparent border-t-transparent border-4',
        left: 'left-full top-1/2 -translate-y-1/2 border-l-gray-900 border-y-transparent border-r-transparent border-4',
        right: 'right-full top-1/2 -translate-y-1/2 border-r-gray-900 border-y-transparent border-l-transparent border-4'
    }
    return arrows[props.position]
})
</script>

<template>
    <div class="relative inline-flex" @mouseenter="show" @mouseleave="hide">
        <!-- Trigger -->
        <slot />

        <!-- Tooltip -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="visible && text"
                :class="[
                    'absolute z-50 whitespace-nowrap rounded-md bg-gray-900 px-2.5 py-1.5 text-xs font-medium text-white shadow-lg pointer-events-none',
                    positionClasses
                ]"
                role="tooltip"
            >
                {{ text }}
                <!-- Arrow -->
                <span :class="['absolute w-0 h-0', arrowClasses]" />
            </div>
        </Transition>
    </div>
</template>
