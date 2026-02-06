<script setup>
import { computed } from 'vue'
import { CheckIcon, XMarkIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    value: {
        type: [Boolean, Number, String],
        default: null
    },
    format: {
        type: String,
        default: 'icon',
        validator: (v) => ['icon', 'text', 'badge', 'yesno'].includes(v)
    },
    trueLabel: {
        type: String,
        default: 'Oui'
    },
    falseLabel: {
        type: String,
        default: 'Non'
    },
    emptyText: {
        type: String,
        default: '-'
    },
    iconStyle: {
        type: String,
        default: 'simple',
        validator: (v) => ['simple', 'circle'].includes(v)
    }
})

const boolValue = computed(() => {
    if (props.value === null || props.value === undefined) return null
    if (typeof props.value === 'boolean') return props.value
    if (typeof props.value === 'number') return props.value !== 0
    if (typeof props.value === 'string') {
        return ['true', '1', 'yes', 'oui'].includes(props.value.toLowerCase())
    }
    return Boolean(props.value)
})

const TrueIcon = computed(() => props.iconStyle === 'circle' ? CheckCircleIcon : CheckIcon)
const FalseIcon = computed(() => props.iconStyle === 'circle' ? XCircleIcon : XMarkIcon)
</script>

<template>
    <span v-if="boolValue === null" class="text-gray-400">
        {{ emptyText }}
    </span>

    <!-- Icon format -->
    <template v-else-if="format === 'icon'">
        <component
            :is="boolValue ? TrueIcon : FalseIcon"
            :class="[
                'h-5 w-5',
                boolValue ? 'text-emerald-500' : 'text-gray-300'
            ]"
        />
    </template>

    <!-- Text format -->
    <template v-else-if="format === 'text'">
        <span :class="boolValue ? 'text-emerald-600' : 'text-gray-500'">
            {{ boolValue ? trueLabel : falseLabel }}
        </span>
    </template>

    <!-- Badge format -->
    <template v-else-if="format === 'badge'">
        <span
            :class="[
                'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                boolValue
                    ? 'bg-emerald-100 text-emerald-700'
                    : 'bg-gray-100 text-gray-700'
            ]"
        >
            {{ boolValue ? trueLabel : falseLabel }}
        </span>
    </template>

    <!-- Yes/No format -->
    <template v-else-if="format === 'yesno'">
        <span
            :class="[
                'inline-flex items-center gap-1',
                boolValue ? 'text-emerald-600' : 'text-red-600'
            ]"
        >
            <component
                :is="boolValue ? CheckIcon : XMarkIcon"
                class="h-4 w-4"
            />
            {{ boolValue ? trueLabel : falseLabel }}
        </span>
    </template>
</template>
