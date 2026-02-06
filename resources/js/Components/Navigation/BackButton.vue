<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ArrowLeftIcon, ChevronLeftIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    href: {
        type: String,
        default: null
    },
    label: {
        type: String,
        default: 'Retour'
    },
    showLabel: {
        type: Boolean,
        default: true
    },
    variant: {
        type: String,
        default: 'default',
        validator: (v) => ['default', 'minimal', 'button'].includes(v)
    },
    iconStyle: {
        type: String,
        default: 'arrow',
        validator: (v) => ['arrow', 'chevron'].includes(v)
    }
})

const emit = defineEmits(['click'])

const IconComponent = computed(() =>
    props.iconStyle === 'chevron' ? ChevronLeftIcon : ArrowLeftIcon
)

const handleClick = (event) => {
    if (!props.href) {
        event.preventDefault()
        emit('click', event)
        // Use browser history if no href specified
        if (window.history.length > 1) {
            window.history.back()
        }
    }
}

const variantClasses = {
    default: 'inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-gray-900 transition-colors',
    minimal: 'inline-flex items-center justify-center h-8 w-8 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors',
    button: 'inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors'
}
</script>

<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        type="button"
        :class="variantClasses[variant]"
        @click="handleClick"
    >
        <IconComponent class="h-5 w-5" />
        <span v-if="showLabel && variant !== 'minimal'">{{ label }}</span>
    </component>
</template>
