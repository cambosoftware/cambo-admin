<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'

const props = defineProps({
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'right'].includes(v)
    },
    width: {
        type: String,
        default: '48',
        validator: (v) => ['32', '40', '48', '56', '64', '72', '80'].includes(v)
    },
    trigger: {
        type: String,
        default: 'click',
        validator: (v) => ['click', 'hover'].includes(v)
    }
})

const open = ref(false)
const dropdownRef = ref(null)
let hoverTimeout = null

const widthClass = computed(() => `w-${props.width}`)
const alignClass = computed(() => props.align === 'right' ? 'right-0' : 'left-0')

const toggle = () => {
    if (props.trigger === 'click') open.value = !open.value
}

const closeDropdown = () => {
    open.value = false
}

const onMouseEnter = () => {
    if (props.trigger !== 'hover') return
    clearTimeout(hoverTimeout)
    open.value = true
}

const onMouseLeave = () => {
    if (props.trigger !== 'hover') return
    hoverTimeout = setTimeout(() => { open.value = false }, 150)
}

const onClickOutside = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        open.value = false
    }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => {
    document.removeEventListener('click', onClickOutside)
    clearTimeout(hoverTimeout)
})
</script>

<template>
    <div
        ref="dropdownRef"
        class="relative inline-block"
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
    >
        <!-- Trigger -->
        <div @click="toggle" class="cursor-pointer">
            <slot name="trigger" :open="open" />
        </div>

        <!-- Menu -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                :class="[
                    'absolute z-50 mt-2 rounded-lg bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-900/50 ring-1 ring-black/5 dark:ring-gray-700 py-1 overflow-hidden',
                    widthClass,
                    alignClass
                ]"
                role="menu"
            >
                <slot :close="closeDropdown" />
            </div>
        </Transition>
    </div>
</template>
