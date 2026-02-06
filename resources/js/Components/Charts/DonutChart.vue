<script setup>
import { computed } from 'vue'
import Chart from './Chart.vue'

const props = defineProps({
    labels: {
        type: Array,
        required: true
    },
    data: {
        type: Array,
        required: true
    },
    colors: {
        type: Array,
        default: () => ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#0ea5e9', '#8b5cf6', '#ec4899', '#14b8a6']
    },
    height: {
        type: [Number, String],
        default: 300
    },
    showLegend: {
        type: Boolean,
        default: true
    },
    cutout: {
        type: String,
        default: '60%'
    },
    showTotal: {
        type: Boolean,
        default: false
    },
    totalLabel: {
        type: String,
        default: 'Total'
    }
})

const total = computed(() => props.data.reduce((sum, val) => sum + val, 0))

const chartData = computed(() => ({
    labels: props.labels,
    datasets: [{
        data: props.data,
        backgroundColor: props.colors,
        borderWidth: 0,
        hoverOffset: 4
    }]
}))

const options = computed(() => ({
    cutout: props.cutout,
    plugins: {
        legend: {
            display: props.showLegend,
            position: 'right'
        }
    }
}))
</script>

<template>
    <div class="relative">
        <Chart
            type="doughnut"
            :data="chartData"
            :options="options"
            :height="height"
        />
        <!-- Center total -->
        <div
            v-if="showTotal"
            class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none"
        >
            <span class="text-2xl font-bold text-gray-900">{{ total.toLocaleString() }}</span>
            <span class="text-sm text-gray-500">{{ totalLabel }}</span>
        </div>
    </div>
</template>
