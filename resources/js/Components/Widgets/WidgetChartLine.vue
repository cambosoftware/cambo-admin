<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
    config: {
        type: Object,
        default: () => ({})
    }
})

// Mock data for demonstration
const chartData = ref([
    { label: 'Jan', value: 65 },
    { label: 'Fév', value: 78 },
    { label: 'Mar', value: 52 },
    { label: 'Avr', value: 89 },
    { label: 'Mai', value: 76 },
    { label: 'Jun', value: 95 },
    { label: 'Jul', value: 88 },
    { label: 'Aoû', value: 102 },
    { label: 'Sep', value: 85 },
    { label: 'Oct', value: 110 },
    { label: 'Nov', value: 98 },
    { label: 'Déc', value: 125 },
])

const maxValue = computed(() => Math.max(...chartData.value.map(d => d.value)) * 1.1)

const getY = (value) => {
    return 100 - (value / maxValue.value * 100)
}

const pathD = computed(() => {
    const points = chartData.value.map((d, i) => {
        const x = (i / (chartData.value.length - 1)) * 100
        const y = getY(d.value)
        return `${x},${y}`
    })
    return `M ${points.join(' L ')}`
})

const areaD = computed(() => {
    const points = chartData.value.map((d, i) => {
        const x = (i / (chartData.value.length - 1)) * 100
        const y = getY(d.value)
        return `${x},${y}`
    })
    return `M 0,100 L ${points.join(' L ')} L 100,100 Z`
})
</script>

<template>
    <div class="h-full flex flex-col p-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ config.title }}
            </h3>
            <div class="flex items-center gap-2">
                <button
                    v-for="period in ['Semaine', 'Mois', 'Année']"
                    :key="period"
                    :class="[
                        'px-2 py-1 text-xs rounded-md transition-colors',
                        period === 'Mois'
                            ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400'
                            : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700'
                    ]"
                >
                    {{ period }}
                </button>
            </div>
        </div>

        <!-- Chart -->
        <div class="flex-1 relative min-h-[150px]">
            <svg
                class="w-full h-full"
                viewBox="0 0 100 100"
                preserveAspectRatio="none"
            >
                <!-- Grid lines -->
                <line
                    v-for="i in 4"
                    :key="i"
                    x1="0"
                    :y1="i * 25"
                    x2="100"
                    :y2="i * 25"
                    class="stroke-gray-200 dark:stroke-gray-700"
                    stroke-width="0.2"
                />

                <!-- Area fill -->
                <path
                    :d="areaD"
                    class="fill-primary-500/10"
                />

                <!-- Line -->
                <path
                    :d="pathD"
                    fill="none"
                    class="stroke-primary-500"
                    stroke-width="0.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <!-- Points -->
                <circle
                    v-for="(d, i) in chartData"
                    :key="i"
                    :cx="(i / (chartData.length - 1)) * 100"
                    :cy="getY(d.value)"
                    r="1"
                    class="fill-primary-500"
                />
            </svg>
        </div>

        <!-- X-axis labels -->
        <div class="flex justify-between mt-2 text-xs text-gray-500">
            <span v-for="(d, i) in chartData" :key="i" v-show="i % 2 === 0">
                {{ d.label }}
            </span>
        </div>
    </div>
</template>
