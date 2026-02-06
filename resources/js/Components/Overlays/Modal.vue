<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg', 'xl', 'full'].includes(v)
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
    },
    title: {
        type: String,
        default: null
    },
    persistent: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['close', 'update:show'])

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'max-w-sm',
        md: 'max-w-lg',
        lg: 'max-w-2xl',
        xl: 'max-w-4xl',
        full: 'max-w-full mx-4'
    }
    return sizes[props.size]
})

const close = () => {
    if (!props.closable && !props.persistent) return
    emit('close')
    emit('update:show', false)
}

const onOverlayClick = () => {
    if (props.closeOnOverlay) close()
}

const onEscape = (e) => {
    if (e.key === 'Escape' && props.show && props.closeOnEscape) {
        close()
    }
}

watch(() => props.show, (val) => {
    if (val) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
    }
})

onMounted(() => {
    document.addEventListener('keydown', onEscape)
})

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
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
            >
                <!-- Overlay -->
                <div
                    class="absolute inset-0 bg-black/50"
                    @click="onOverlayClick"
                />

                <!-- Modal -->
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                    appear
                >
                    <div
                        v-if="show"
                        :class="[
                            'relative w-full bg-white dark:bg-gray-800 rounded-xl shadow-2xl dark:shadow-gray-900/50 overflow-hidden',
                            sizeClasses
                        ]"
                        role="dialog"
                        aria-modal="true"
                    >
                        <!-- Header -->
                        <div
                            v-if="title || $slots.header || closable"
                            class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700"
                        >
                            <div class="flex-1 min-w-0">
                                <slot name="header">
                                    <h3 v-if="title" class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                        {{ title }}
                                    </h3>
                                </slot>
                            </div>
                            <button
                                v-if="closable"
                                type="button"
                                class="ml-4 p-1 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                @click="close"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                            <slot />
                        </div>

                        <!-- Footer -->
                        <div
                            v-if="$slots.footer"
                            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
                        >
                            <slot name="footer" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
