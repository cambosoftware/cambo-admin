<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    title: {
        type: String,
        default: null
    },
    subtitle: {
        type: String,
        default: null
    },
    showLogo: {
        type: Boolean,
        default: true
    },
    maxWidth: {
        type: String,
        default: 'sm',
        validator: (v) => ['xs', 'sm', 'md', 'lg'].includes(v)
    }
})

const maxWidthClass = computed(() => {
    const widths = {
        xs: 'max-w-xs',
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg'
    }
    return widths[props.maxWidth]
})
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
        <div class="sm:mx-auto sm:w-full" :class="maxWidthClass">
            <!-- Logo -->
            <div v-if="showLogo" class="flex justify-center">
                <Link href="/">
                    <slot name="logo">
                        <img
                            src="https://cambo-admin.cambosoftware.com/images/logo-header.png"
                            alt="CamboAdmin"
                            class="h-12 w-auto"
                        />
                    </slot>
                </Link>
            </div>

            <!-- Title -->
            <h2 v-if="title" class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                {{ title }}
            </h2>

            <!-- Subtitle -->
            <p v-if="subtitle" class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                {{ subtitle }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full" :class="maxWidthClass">
            <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-lg dark:shadow-gray-900/50 sm:rounded-xl sm:px-10 transition-colors duration-200">
                <slot />
            </div>

            <!-- Footer slot -->
            <div v-if="$slots.footer" class="mt-6">
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>
