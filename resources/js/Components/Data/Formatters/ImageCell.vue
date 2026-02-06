<script setup>
import { ref } from 'vue'
import { PhotoIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    value: {
        type: String,
        default: null
    },
    alt: {
        type: String,
        default: ''
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg'].includes(v)
    },
    rounded: {
        type: String,
        default: 'md',
        validator: (v) => ['none', 'sm', 'md', 'lg', 'full'].includes(v)
    },
    fallback: {
        type: String,
        default: null
    },
    lightbox: {
        type: Boolean,
        default: true
    }
})

const hasError = ref(false)
const showLightbox = ref(false)

const handleError = () => {
    hasError.value = true
}

const sizeClasses = {
    xs: 'h-6 w-6',
    sm: 'h-8 w-8',
    md: 'h-10 w-10',
    lg: 'h-14 w-14'
}

const roundedClasses = {
    none: 'rounded-none',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    full: 'rounded-full'
}
</script>

<template>
    <div class="inline-flex">
        <!-- No image -->
        <div
            v-if="!value || hasError"
            :class="[
                'flex items-center justify-center bg-gray-100',
                sizeClasses[size],
                roundedClasses[rounded]
            ]"
        >
            <img
                v-if="fallback && !hasError"
                :src="fallback"
                :alt="alt"
                :class="['object-cover', sizeClasses[size], roundedClasses[rounded]]"
                @error="hasError = true"
            />
            <PhotoIcon v-else class="h-1/2 w-1/2 text-gray-400" />
        </div>

        <!-- Image -->
        <button
            v-else
            type="button"
            :class="[
                'overflow-hidden bg-gray-100',
                sizeClasses[size],
                roundedClasses[rounded],
                lightbox ? 'cursor-pointer hover:opacity-90 transition-opacity' : ''
            ]"
            @click="lightbox && (showLightbox = true)"
        >
            <img
                :src="value"
                :alt="alt"
                class="h-full w-full object-cover"
                @error="handleError"
            />
        </button>

        <!-- Lightbox -->
        <teleport to="body">
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showLightbox"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4"
                    @click="showLightbox = false"
                >
                    <img
                        :src="value"
                        :alt="alt"
                        class="max-h-full max-w-full object-contain rounded-lg"
                        @click.stop
                    />
                </div>
            </transition>
        </teleport>
    </div>
</template>
