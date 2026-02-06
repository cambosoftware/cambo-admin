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
        // [{ label: 'Series 1', data: [1, 2, 3], color: '#6366f1' }]
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
    fill: {
        type: Boolean,
        default: false
    }
})

const colors = {
    primary: { bg: 'rgba(99, 102, 241, 0.1)', border: '#6366f1' },
    success: { bg: 'rgba(16, 185, 129, 0.1)', border: '#10b981' },
    warning: { bg: 'rgba(245, 158, 11, 0.1)', border: '#f59e0b' },
    danger: { bg: 'rgba(239, 68, 68, 0.1)', border: '#ef4444' },
    info: { bg: 'rgba(14, 165, 233, 0.1)', border: '#0ea5e9' },
    secondary: { bg: 'rgba(107, 114, 128, 0.1)', border: '#6b7280' }
}

const getColor = (colorName) => {
    return colors[colorName] || { bg: colorName + '20', border: colorName }
}

const chartData = computed(() => ({
    labels: props.labels,
    datasets: props.datasets.map((ds, index) => {
        const color = getColor(ds.color || ['primary', 'success', 'warning', 'danger', 'info'][index % 5])
        return {
            label: ds.label,
            data: ds.data,
            borderColor: color.border,
            backgroundColor: props.fill ? color.bg : 'transparent',
            tension: props.tension,
            fill: props.fill,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#fff',
            pointBorderColor: color.border,
            pointBorderWidth: 2
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
                display: props.showGrid
            }
        },
        y: {
            grid: {
                display: props.showGrid
            }
        }
    }
}))
</script>

<template>
    <Chart
        type="line"
        :data="chartData"
        :options="options"
        :height="height"
    />
</template>
