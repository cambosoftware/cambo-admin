<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    src: {
        type: String,
        default: null
    },
    alt: {
        type: String,
        default: ''
    },
    name: {
        type: String,
        default: ''
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg', 'xl', '2xl'].includes(v)
    },
    rounded: {
        type: String,
        default: 'full',
        validator: (v) => ['sm', 'md', 'lg', 'full'].includes(v)
    },
    status: {
        type: String,
        default: null,
        validator: (v) => v === null || ['online', 'offline', 'busy', 'away'].includes(v)
    },
    color: {
        type: String,
        default: null
    }
})

const imgError = ref(false)

const initials = computed(() => {
    if (!props.name) return '?'
    return props.name
        .split(' ')
        .map(word => word.charAt(0))
        .slice(0, 2)
        .join('')
        .toUpperCase()
})

const showImage = computed(() => props.src && !imgError.value)

const sizeClasses = computed(() => {
    const sizes = {
        xs: 'h-6 w-6 text-xs',
        sm: 'h-8 w-8 text-xs',
        md: 'h-10 w-10 text-sm',
        lg: 'h-12 w-12 text-base',
        xl: 'h-16 w-16 text-lg',
        '2xl': 'h-20 w-20 text-xl'
    }
    return sizes[props.size]
})

const roundedClasses = computed(() => {
    const roundedMap = {
        sm: 'rounded-sm',
        md: 'rounded-md',
        lg: 'rounded-lg',
        full: 'rounded-full'
    }
    return roundedMap[props.rounded]
})

const statusSizeClasses = computed(() => {
    const sizes = {
        xs: 'h-1.5 w-1.5',
        sm: 'h-2 w-2',
        md: 'h-2.5 w-2.5',
        lg: 'h-3 w-3',
        xl: 'h-3.5 w-3.5',
        '2xl': 'h-4 w-4'
    }
    return sizes[props.size]
})

const statusColor = computed(() => {
    const colors = {
        online: 'bg-emerald-500',
        offline: 'bg-gray-400',
        busy: 'bg-red-500',
        away: 'bg-amber-500'
    }
    return colors[props.status]
})

const bgColorClass = computed(() => {
    if (props.color) return props.color
    // Generate a deterministic color from the name
    const colors = [
        'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500',
        'bg-rose-500', 'bg-purple-500', 'bg-sky-500',
        'bg-teal-500', 'bg-orange-500'
    ]
    if (!props.name) return colors[0]
    const index = props.name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0)
    return colors[index % colors.length]
})
</script>

<template>
    <span class="relative inline-flex flex-shrink-0">
        <!-- Image -->
        <img
            v-if="showImage"
            :src="src"
            :alt="alt || name"
            :class="[
                'object-cover',
                sizeClasses,
                roundedClasses
            ]"
            @error="imgError = true"
        />

        <!-- Initials fallback -->
        <span
            v-else
            :class="[
                'inline-flex items-center justify-center font-semibold text-white',
                sizeClasses,
                roundedClasses,
                bgColorClass
            ]"
        >
            {{ initials }}
        </span>

        <!-- Status indicator -->
        <span
            v-if="status"
            :class="[
                'absolute bottom-0 right-0 block rounded-full ring-2 ring-white',
                statusSizeClasses,
                statusColor
            ]"
        />
    </span>
</template>
