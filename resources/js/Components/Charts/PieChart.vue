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
    }
})

const chartData = computed(() => ({
    labels: props.labels,
    datasets: [{
        data: props.data,
        backgroundColor: props.colors,
        borderWidth: 2,
        borderColor: '#fff',
        hoverOffset: 8
    }]
}))

const options = computed(() => ({
    plugins: {
        legend: {
            display: props.showLegend,
            position: 'right'
        }
    }
}))
</script>

<template>
    <Chart
        type="pie"
        :data="chartData"
        :options="options"
        :height="height"
    />
</template>
