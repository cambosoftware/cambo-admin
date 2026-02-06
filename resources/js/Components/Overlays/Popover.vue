<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    position: {
        type: String,
        default: 'bottom',
        validator: (v) => ['top', 'bottom', 'left', 'right'].includes(v)
    },
    trigger: {
        type: String,
        default: 'click',
        validator: (v) => ['click', 'hover'].includes(v)
    },
    width: {
        type: String,
        default: null
    }
})

const open = ref(false)
const popoverRef = ref(null)
let hoverTimeout = null

const positionClasses = computed(() => {
    const positions = {
        top: 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
        left: 'right-full top-1/2 -translate-y-1/2 mr-2',
        right: 'left-full top-1/2 -translate-y-1/2 ml-2'
    }
    return positions[props.position]
})

const toggle = () => {
    if (props.trigger === 'click') open.value = !open.value
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
    if (popoverRef.value && !popoverRef.value.contains(e.target)) {
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
        ref="popoverRef"
        class="relative inline-flex"
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
    >
        <!-- Trigger -->
        <div @click="toggle" class="cursor-pointer">
            <slot name="trigger" :open="open" />
        </div>

        <!-- Content -->
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
                    'absolute z-50 rounded-lg bg-white shadow-lg ring-1 ring-black/5 p-4',
                    positionClasses
                ]"
                :style="width ? { width } : {}"
            >
                <slot :close="() => open = false" />
            </div>
        </Transition>
    </div>
</template>
