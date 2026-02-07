<script setup>
import { computed } from 'vue'
import { CheckCircleIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        default: null
    },
    date: {
        type: String,
        default: null
    },
    icon: {
        type: Object,
        default: null
    },
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'primary', 'success', 'warning', 'danger', 'info'].includes(v)
    },
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'right'].includes(v)
    },
    last: {
        type: Boolean,
        default: false
    }
})

const variantClasses = computed(() => {
    const variants = {
        default: 'bg-gray-100 text-gray-500 ring-gray-200',
        primary: 'bg-indigo-100 text-indigo-600 ring-indigo-200',
        success: 'bg-emerald-100 text-emerald-600 ring-emerald-200',
        warning: 'bg-amber-100 text-amber-600 ring-amber-200',
        danger: 'bg-red-100 text-red-600 ring-red-200',
        info: 'bg-sky-100 text-sky-600 ring-sky-200'
    }
    return variants[props.variant]
})

const IconComponent = computed(() => props.icon || CheckCircleIcon)
</script>

<template>
    <div
        :class="[
            'relative flex gap-4',
            align === 'right' ? 'flex-row-reverse text-right' : ''
        ]"
    >
        <!-- Dot/Icon -->
        <div
            :class="[
                'relative z-10 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center ring-4 ring-white',
                variantClasses
            ]"
        >
            <component :is="IconComponent" class="w-4 h-4" />
        </div>

        <!-- Line (for alternate mode) -->
        <div
            v-if="!last"
            :class="[
                'absolute top-8 w-0.5 h-full bg-gray-200',
                align === 'left' ? 'left-4' : 'right-4'
            ]"
            style="transform: translateX(-50%)"
        />

        <!-- Content -->
        <div class="flex-1 min-w-0 pb-6">
            <div class="flex items-center gap-2 flex-wrap">
                <h4 class="text-sm font-medium text-gray-900">{{ title }}</h4>
                <time v-if="date" class="text-xs text-gray-500">{{ date }}</time>
            </div>
            <p v-if="description" class="mt-1 text-sm text-gray-600">
                {{ description }}
            </p>
            <div v-if="$slots.default" class="mt-2">
                <slot />
            </div>
        </div>
    </div>
</template>
