<script setup>
import { computed } from 'vue'
import { ArrowUpIcon, ArrowDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    value: {
        type: [String, Number],
        required: true
    },
    previousValue: {
        type: [String, Number],
        default: null
    },
    change: {
        type: [String, Number],
        default: null
    },
    changeLabel: {
        type: String,
        default: 'vs période précédente'
    },
    format: {
        type: String,
        default: 'number',
        validator: (v) => ['number', 'currency', 'percent', 'custom'].includes(v)
    },
    currency: {
        type: String,
        default: 'EUR'
    },
    locale: {
        type: String,
        default: 'fr-FR'
    },
    icon: {
        type: Object,
        default: null
    },
    iconColor: {
        type: String,
        default: 'primary'
    },
    trend: {
        type: String,
        default: null,
        validator: (v) => [null, 'up', 'down', 'neutral'].includes(v)
    },
    invertTrend: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    href: {
        type: String,
        default: null
    }
})

const formattedValue = computed(() => {
    if (props.format === 'custom') return props.value

    const num = typeof props.value === 'string' ? parseFloat(props.value) : props.value
    if (isNaN(num)) return props.value

    switch (props.format) {
        case 'currency':
            return new Intl.NumberFormat(props.locale, {
                style: 'currency',
                currency: props.currency,
                maximumFractionDigits: 0
            }).format(num)
        case 'percent':
            return new Intl.NumberFormat(props.locale, {
                style: 'percent',
                maximumFractionDigits: 1
            }).format(num / 100)
        default:
            return new Intl.NumberFormat(props.locale, {
                notation: num >= 10000 ? 'compact' : 'standard',
                maximumFractionDigits: 1
            }).format(num)
    }
})

const computedChange = computed(() => {
    if (props.change !== null) return props.change
    if (props.previousValue === null) return null

    const current = typeof props.value === 'string' ? parseFloat(props.value) : props.value
    const previous = typeof props.previousValue === 'string' ? parseFloat(props.previousValue) : props.previousValue

    if (isNaN(current) || isNaN(previous) || previous === 0) return null
    return ((current - previous) / previous) * 100
})

const computedTrend = computed(() => {
    if (props.trend) return props.trend
    if (computedChange.value === null) return null
    if (computedChange.value > 0) return 'up'
    if (computedChange.value < 0) return 'down'
    return 'neutral'
})

const isPositive = computed(() => {
    const positive = computedTrend.value === 'up'
    return props.invertTrend ? !positive : positive
})

const trendColor = computed(() => {
    if (computedTrend.value === 'neutral') return 'text-gray-500'
    return isPositive.value ? 'text-emerald-600' : 'text-red-600'
})

const iconBgColors = {
    primary: 'bg-indigo-100 text-indigo-600',
    success: 'bg-emerald-100 text-emerald-600',
    warning: 'bg-amber-100 text-amber-600',
    danger: 'bg-red-100 text-red-600',
    info: 'bg-sky-100 text-sky-600',
    secondary: 'bg-gray-100 text-gray-600'
}
</script>

<template>
    <component
        :is="href ? 'a' : 'div'"
        :href="href"
        :class="[
            'relative overflow-hidden rounded-xl bg-white p-6 ring-1 ring-gray-200',
            href ? 'hover:ring-indigo-300 transition-shadow cursor-pointer' : ''
        ]"
    >
        <!-- Loading skeleton -->
        <div v-if="loading" class="animate-pulse">
            <div class="flex items-center justify-between">
                <div class="h-4 w-24 bg-gray-200 rounded" />
                <div class="h-10 w-10 bg-gray-200 rounded-full" />
            </div>
            <div class="mt-4 h-8 w-32 bg-gray-200 rounded" />
            <div class="mt-2 h-4 w-20 bg-gray-200 rounded" />
        </div>

        <!-- Content -->
        <template v-else>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ title }}</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">
                        {{ formattedValue }}
                    </p>
                </div>
                <div
                    v-if="icon"
                    :class="[
                        'flex h-12 w-12 items-center justify-center rounded-full',
                        iconBgColors[iconColor]
                    ]"
                >
                    <component :is="icon" class="h-6 w-6" />
                </div>
            </div>

            <!-- Trend indicator -->
            <div v-if="computedChange !== null" class="mt-4 flex items-center gap-1">
                <span
                    :class="[
                        'inline-flex items-center gap-0.5 text-sm font-medium',
                        trendColor
                    ]"
                >
                    <ArrowUpIcon v-if="computedTrend === 'up'" class="h-4 w-4" />
                    <ArrowDownIcon v-else-if="computedTrend === 'down'" class="h-4 w-4" />
                    {{ Math.abs(computedChange).toFixed(1) }}%
                </span>
                <span class="text-sm text-gray-500">{{ changeLabel }}</span>
            </div>

            <!-- Mini chart slot -->
            <div v-if="$slots.chart" class="mt-4">
                <slot name="chart" />
            </div>
        </template>
    </component>
</template>
