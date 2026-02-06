<script setup>
import { computed } from 'vue'
import Chart from './Chart.vue'

const props = defineProps({
    labels: {
        type: Array,
        required: true
    },
    datasets: {
        type: Array,
        required: true
        // [{ label: 'Series 1', data: [1, 2, 3], color: 'primary' }]
    },
    height: {
        type: [Number, String],
        default: 300
    },
    showLegend: {
        type: Boolean,
        default: true
    },
    showGrid: {
        type: Boolean,
        default: true
    },
    tension: {
        type: Number,
        default: 0.4
    },
    stacked: {
        type: Boolean,
        default: false
    }
})

const colors = {
    primary: { bg: 'rgba(99, 102, 241, 0.3)', border: '#6366f1' },
    success: { bg: 'rgba(16, 185, 129, 0.3)', border: '#10b981' },
    warning: { bg: 'rgba(245, 158, 11, 0.3)', border: '#f59e0b' },
    danger: { bg: 'rgba(239, 68, 68, 0.3)', border: '#ef4444' },
    info: { bg: 'rgba(14, 165, 233, 0.3)', border: '#0ea5e9' },
    secondary: { bg: 'rgba(107, 114, 128, 0.3)', border: '#6b7280' }
}

const getColor = (colorName) => {
    return colors[colorName] || { bg: colorName + '4D', border: colorName }
}

const chartData = computed(() => ({
    labels: props.labels,
    datasets: props.datasets.map((ds, index) => {
        const color = getColor(ds.color || ['primary', 'success', 'warning', 'danger', 'info'][index % 5])
        return {
            label: ds.label,
            data: ds.data,
            borderColor: color.border,
            backgroundColor: color.bg,
            tension: props.tension,
            fill: true,
            pointRadius: 0,
            pointHoverRadius: 4,
            pointBackgroundColor: color.border,
            borderWidth: 2
        }
    })
}))

const options = computed(() => ({
    plugins: {
        legend: {
            display: props.showLegend
        }
    },
    scales: {
        x: {
            grid: {
                display: false
            },
            stacked: props.stacked
        },
        y: {
            grid: {
                display: props.showGrid
            },
            stacked: props.stacked
        }
    }
}))
</script>

<template>
    <Chart
        type="area"
        :data="chartData"
        :options="options"
        :height="height"
    />
</template>
