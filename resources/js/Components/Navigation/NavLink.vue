<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    href: {
        type: String,
        required: true
    },
    active: {
        type: Boolean,
        default: null
    },
    exact: {
        type: Boolean,
        default: false
    },
    icon: {
        type: Object,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    },
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'pills', 'underline', 'minimal'].includes(v)
    }
})

const page = usePage()

const isActive = computed(() => {
    if (props.active !== null) return props.active
    const currentUrl = page.url
    if (props.exact) {
        return currentUrl === props.href
    }
    return currentUrl.startsWith(props.href)
})

const variantClasses = computed(() => {
    const variants = {
        default: {
            base: 'inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-colors',
            active: 'bg-primary-100 text-primary-700',
            inactive: 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
        },
        pills: {
            base: 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full transition-colors',
            active: 'bg-primary-500 text-white',
            inactive: 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
        },
        underline: {
            base: 'inline-flex items-center gap-2 px-1 py-2 text-sm font-medium border-b-2 transition-colors',
            active: 'border-primary-500 text-primary-600',
            inactive: 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
        },
        minimal: {
            base: 'inline-flex items-center gap-2 text-sm font-medium transition-colors',
            active: 'text-primary-600',
            inactive: 'text-gray-500 hover:text-gray-900'
        }
    }
    return variants[props.variant]
})
</script>

<template>
    <Link
        :href="disabled ? '#' : href"
        :class="[
            variantClasses.base,
            isActive ? variantClasses.active : variantClasses.inactive,
            disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''
        ]"
        :aria-current="isActive ? 'page' : undefined"
    >
        <component
            v-if="icon"
            :is="icon"
            :class="[
                'h-5 w-5',
                isActive ? 'text-current' : 'text-gray-400 group-hover:text-gray-500'
            ]"
        />
        <slot />
    </Link>
</template>
