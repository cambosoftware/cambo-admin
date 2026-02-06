<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    href: {
        type: String,
        required: true
    },
    external: {
        type: Boolean,
        default: false
    },
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'muted', 'primary'].includes(v)
    },
    underline: {
        type: String,
        default: 'hover',
        validator: (v) => ['always', 'hover', 'none'].includes(v)
    }
})

const variantClasses = computed(() => {
    const variants = {
        default: 'text-primary-600 hover:text-primary-700',
        muted: 'text-gray-500 hover:text-gray-700',
        primary: 'text-primary-600 hover:text-primary-800 font-semibold'
    }
    return variants[props.variant]
})

const underlineClasses = computed(() => {
    const underlines = {
        always: 'underline',
        hover: 'hover:underline',
        none: 'no-underline'
    }
    return underlines[props.underline]
})
</script>

<template>
    <!-- External link -->
    <a
        v-if="external"
        :href="href"
        target="_blank"
        rel="noopener noreferrer"
        :class="[
            'inline-flex items-center gap-1 transition-colors duration-150',
            variantClasses,
            underlineClasses
        ]"
    >
        <slot />
        <svg class="h-3.5 w-3.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5zm7.25-.75a.75.75 0 01.75-.75h3.5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0V6.31l-5.47 5.47a.75.75 0 01-1.06-1.06l5.47-5.47H12.25a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
        </svg>
    </a>

    <!-- Internal Inertia link -->
    <Link
        v-else
        :href="href"
        :class="[
            'transition-colors duration-150',
            variantClasses,
            underlineClasses
        ]"
    >
        <slot />
    </Link>
</template>
