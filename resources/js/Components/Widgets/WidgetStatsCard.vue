<script setup>
import { computed } from 'vue'
import {
    UsersIcon,
    ShoppingCartIcon,
    CurrencyDollarIcon,
    CubeIcon,
    ChartBarIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    config: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    }
})

const iconMap = {
    users: UsersIcon,
    'shopping-cart': ShoppingCartIcon,
    'currency-dollar': CurrencyDollarIcon,
    cube: CubeIcon,
    'chart-bar': ChartBarIcon,
}

const colorClasses = {
    primary: {
        bg: 'bg-primary-100 dark:bg-primary-900/30',
        text: 'text-primary-600 dark:text-primary-400',
        icon: 'text-primary-500',
    },
    success: {
        bg: 'bg-green-100 dark:bg-green-900/30',
        text: 'text-green-600 dark:text-green-400',
        icon: 'text-green-500',
    },
    warning: {
        bg: 'bg-amber-100 dark:bg-amber-900/30',
        text: 'text-amber-600 dark:text-amber-400',
        icon: 'text-amber-500',
    },
    danger: {
        bg: 'bg-red-100 dark:bg-red-900/30',
        text: 'text-red-600 dark:text-red-400',
        icon: 'text-red-500',
    },
    info: {
        bg: 'bg-blue-100 dark:bg-blue-900/30',
        text: 'text-blue-600 dark:text-blue-400',
        icon: 'text-blue-500',
    },
}

const icon = computed(() => iconMap[props.config.icon] || ChartBarIcon)
const colors = computed(() => colorClasses[props.config.color] || colorClasses.primary)
const value = computed(() => {
    const val = props.stats[props.config.stat_key] || 0
    if (props.config.stat_key === 'revenue') {
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(val)
    }
    return new Intl.NumberFormat('fr-FR').format(val)
})

// Mock trend data
const trend = computed(() => ({
    value: Math.floor(Math.random() * 20) - 5,
    isUp: Math.random() > 0.3
}))
</script>

<template>
    <div class="h-full p-4 flex items-center gap-4">
        <!-- Icon -->
        <div
            :class="[
                'flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center',
                colors.bg
            ]"
        >
            <component :is="icon" :class="['h-7 w-7', colors.icon]" />
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                {{ config.title }}
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ value }}
            </p>

            <!-- Trend -->
            <div
                v-if="config.show_trend !== false"
                class="flex items-center gap-1 mt-1"
            >
                <component
                    :is="trend.isUp ? ArrowTrendingUpIcon : ArrowTrendingDownIcon"
                    :class="[
                        'h-4 w-4',
                        trend.isUp ? 'text-green-500' : 'text-red-500'
                    ]"
                />
                <span
                    :class="[
                        'text-sm font-medium',
                        trend.isUp ? 'text-green-600' : 'text-red-600'
                    ]"
                >
                    {{ trend.isUp ? '+' : '' }}{{ trend.value }}%
                </span>
                <span class="text-xs text-gray-500">vs mois dernier</span>
            </div>
        </div>
    </div>
</template>
