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
    horizontal: {
        type: Boolean,
        default: false
    },
    stacked: {
        type: Boolean,
        default: false
    },
    borderRadius: {
        type: Number,
        default: 4
    }
})

const colors = {
    primary: '#6366f1',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#0ea5e9',
    secondary: '#6b7280'
}

const getColor = (colorName) => {
    return colors[colorName] || colorName
}

const chartData = computed(() => ({
    labels: props.labels,
    datasets: props.datasets.map((ds, index) => {
        const color = getColor(ds.color || ['primary', 'success', 'warning', 'danger', 'info'][index % 5])
        return {
            label: ds.label,
            data: ds.data,
            backgroundColor: color,
            borderRadius: props.borderRadius,
            borderSkipped: false
        }
    })
}))

const options = computed(() => ({
    indexAxis: props.horizontal ? 'y' : 'x',
    plugins: {
        legend: {
            display: props.showLegend
        }
    },
    scales: {
        x: {
            grid: {
                display: props.horizontal ? props.showGrid : false
            },
            stacked: props.stacked
        },
        y: {
            grid: {
                display: props.horizontal ? false : props.showGrid
            },
            stacked: props.stacked
        }
    }
}))
</script>

<template>
    <Chart
        type="bar"
        :data="chartData"
        :options="options"
        :height="height"
    />
</template>
