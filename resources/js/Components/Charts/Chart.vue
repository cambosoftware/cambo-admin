<script setup>
import { computed } from 'vue'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js'
import { Line, Bar, Doughnut, Pie } from 'vue-chartjs'

// Register Chart.js components
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler
)

const props = defineProps({
    type: {
        type: String,
        required: true,
        validator: (v) => ['line', 'bar', 'doughnut', 'pie', 'area'].includes(v)
    },
    data: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    },
    height: {
        type: [Number, String],
        default: null
    },
    width: {
        type: [Number, String],
        default: null
    }
})

const chartComponent = computed(() => {
    const components = {
        line: Line,
        bar: Bar,
        doughnut: Doughnut,
        pie: Pie,
        area: Line
    }
    return components[props.type]
})

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                usePointStyle: true,
                padding: 20,
                font: {
                    family: 'Inter, system-ui, sans-serif',
                    size: 12
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(17, 24, 39, 0.9)',
            titleFont: {
                family: 'Inter, system-ui, sans-serif',
                size: 13,
                weight: '600'
            },
            bodyFont: {
                family: 'Inter, system-ui, sans-serif',
                size: 12
            },
            padding: 12,
            cornerRadius: 8,
            displayColors: true,
            boxPadding: 4
        }
    },
    scales: props.type === 'doughnut' || props.type === 'pie' ? {} : {
        x: {
            grid: {
                display: false
            },
            ticks: {
                font: {
                    family: 'Inter, system-ui, sans-serif',
                    size: 11
                }
            }
        },
        y: {
            grid: {
                color: 'rgba(156, 163, 175, 0.2)'
            },
            ticks: {
                font: {
                    family: 'Inter, system-ui, sans-serif',
                    size: 11
                }
            }
        }
    }
}

const mergedOptions = computed(() => {
    return {
        ...defaultOptions,
        ...props.options,
        plugins: {
            ...defaultOptions.plugins,
            ...props.options.plugins
        }
    }
})

const chartData = computed(() => {
    // For area chart, ensure fill is enabled
    if (props.type === 'area' && props.data.datasets) {
        return {
            ...props.data,
            datasets: props.data.datasets.map(ds => ({
                ...ds,
                fill: true,
                tension: ds.tension ?? 0.4
            }))
        }
    }
    return props.data
})

const style = computed(() => ({
    height: props.height ? (typeof props.height === 'number' ? `${props.height}px` : props.height) : undefined,
    width: props.width ? (typeof props.width === 'number' ? `${props.width}px` : props.width) : undefined
}))
</script>

<template>
    <div :style="style">
        <component
            :is="chartComponent"
            :data="chartData"
            :options="mergedOptions"
        />
    </div>
</template>
