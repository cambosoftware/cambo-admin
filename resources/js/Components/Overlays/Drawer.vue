<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    position: {
        type: String,
        default: 'right',
        validator: (v) => ['left', 'right', 'top', 'bottom'].includes(v)
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg', 'xl', 'full'].includes(v)
    },
    title: {
        type: String,
        default: null
    },
    closable: {
        type: Boolean,
        default: true
    },
    closeOnOverlay: {
        type: Boolean,
        default: true
    },
    closeOnEscape: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['close', 'update:show'])

const isHorizontal = computed(() => ['left', 'right'].includes(props.position))

const sizeClasses = computed(() => {
    if (!isHorizontal.value) {
        const heights = { sm: 'max-h-1/4', md: 'max-h-1/3', lg: 'max-h-1/2', xl: 'max-h-3/4', full: 'max-h-full' }
        return heights[props.size]
    }
    const widths = { sm: 'max-w-xs', md: 'max-w-sm', lg: 'max-w-lg', xl: 'max-w-2xl', full: 'max-w-full' }
    return widths[props.size]
})

const panelClasses = computed(() => {
    const base = 'fixed bg-white shadow-2xl flex flex-col'
    const pos = {
        left: `${base} inset-y-0 left-0 w-full ${sizeClasses.value}`,
        right: `${base} inset-y-0 right-0 w-full ${sizeClasses.value}`,
        top: `${base} inset-x-0 top-0 h-full ${sizeClasses.value}`,
        bottom: `${base} inset-x-0 bottom-0 h-full ${sizeClasses.value}`
    }
    return pos[props.position]
})

const enterFrom = computed(() => {
    const transforms = {
        left: '-translate-x-full',
        right: 'translate-x-full',
        top: '-translate-y-full',
        bottom: 'translate-y-full'
    }
    return transforms[props.position]
})

const close = () => {
    emit('close')
    emit('update:show', false)
}

const onOverlayClick = () => {
    if (props.closeOnOverlay) close()
}

const onEscape = (e) => {
    if (e.key === 'Escape' && props.show && props.closeOnEscape) close()
}

watch(() => props.show, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})

onMounted(() => document.addEventListener('keydown', onEscape))
onUnmounted(() => {
    document.removeEventListener('keydown', onEscape)
    document.body.style.overflow = ''
})
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50" @click="onOverlayClick" />

                <!-- Panel -->
                <Transition
                    :enter-active-class="'transition duration-300 ease-out transform'"
                    :enter-from-class="enterFrom"
                    enter-to-class="translate-x-0 translate-y-0"
                    :leave-active-class="'transition duration-200 ease-in transform'"
                    leave-from-class="translate-x-0 translate-y-0"
                    :leave-to-class="enterFrom"
                    appear
                >
                    <div v-if="show" :class="panelClasses">
                        <!-- Header -->
                        <div
                            v-if="title || $slots.header || closable"
                            class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0"
                        >
                            <div class="flex-1 min-w-0">
                                <slot name="header">
                                    <h3 v-if="title" class="text-lg font-semibold text-gray-900 truncate">
                                        {{ title }}
                                    </h3>
                                </slot>
                            </div>
                            <button
                                v-if="closable"
                                type="button"
                                class="ml-4 p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                                @click="close"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <slot />
                        </div>

                        <!-- Footer -->
                        <div
                            v-if="$slots.footer"
                            class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0"
                        >
                            <slot name="footer" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
