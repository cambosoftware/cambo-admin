<script setup>
import { computed, ref, onMounted } from 'vue'

const props = defineProps({
    data: {
        type: Array,
        required: true
        // Array of numbers
    },
    type: {
        type: String,
        default: 'line',
        validator: (v) => ['line', 'bar', 'area'].includes(v)
    },
    color: {
        type: String,
        default: 'primary'
    },
    height: {
        type: Number,
        default: 40
    },
    showDots: {
        type: Boolean,
        default: false
    },
    animated: {
        type: Boolean,
        default: true
    }
})

const svgRef = ref(null)

const colorMap = {
    primary: { stroke: '#6366f1', fill: 'rgba(99, 102, 241, 0.1)' },
    success: { stroke: '#10b981', fill: 'rgba(16, 185, 129, 0.1)' },
    warning: { stroke: '#f59e0b', fill: 'rgba(245, 158, 11, 0.1)' },
    danger: { stroke: '#ef4444', fill: 'rgba(239, 68, 68, 0.1)' },
    info: { stroke: '#0ea5e9', fill: 'rgba(14, 165, 233, 0.1)' }
}

const chartColor = computed(() => colorMap[props.color] || colorMap.primary)

const normalizedData = computed(() => {
    if (!props.data.length) return []
    const min = Math.min(...props.data)
    const max = Math.max(...props.data)
    const range = max - min || 1
    return props.data.map(v => 1 - (v - min) / range)
})

const points = computed(() => {
    const padding = 4
    const width = 100 - padding * 2
    const height = props.height - padding * 2

    return normalizedData.value.map((value, index) => {
        const x = padding + (index / (normalizedData.value.length - 1)) * width
        const y = padding + value * height
        return { x, y }
    })
})

const linePath = computed(() => {
    if (points.value.length < 2) return ''
    return points.value.map((p, i) =>
        i === 0 ? `M ${p.x} ${p.y}` : `L ${p.x} ${p.y}`
    ).join(' ')
})

const areaPath = computed(() => {
    if (points.value.length < 2) return ''
    const height = props.height
    const first = points.value[0]
    const last = points.value[points.value.length - 1]
    return `${linePath.value} L ${last.x} ${height} L ${first.x} ${height} Z`
})

const barWidth = computed(() => {
    const padding = 4
    const gap = 2
    const availableWidth = 100 - padding * 2
    return (availableWidth - (props.data.length - 1) * gap) / props.data.length
})

const bars = computed(() => {
    const padding = 4
    const gap = 2
    const height = props.height - padding * 2

    return normalizedData.value.map((value, index) => {
        const barHeight = (1 - value) * height
        return {
            x: padding + index * (barWidth.value + gap),
            y: padding + value * height,
            width: barWidth.value,
            height: barHeight
        }
    })
})
</script>

<template>
    <svg
        ref="svgRef"
        :height="height"
        viewBox="0 0 100 40"
        preserveAspectRatio="none"
        class="w-full overflow-visible"
    >
        <!-- Area fill -->
        <path
            v-if="type === 'area' || type === 'line'"
            :d="areaPath"
            :fill="type === 'area' ? chartColor.fill : 'transparent'"
            class="transition-all duration-500"
        />

        <!-- Line -->
        <path
            v-if="type === 'line' || type === 'area'"
            :d="linePath"
            :stroke="chartColor.stroke"
            stroke-width="2"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="transition-all duration-500"
        />

        <!-- Dots -->
        <g v-if="showDots && (type === 'line' || type === 'area')">
            <circle
                v-for="(point, index) in points"
                :key="index"
                :cx="point.x"
                :cy="point.y"
                r="2"
                :fill="chartColor.stroke"
                class="transition-all duration-500"
            />
        </g>

        <!-- Bars -->
        <g v-if="type === 'bar'">
            <rect
                v-for="(bar, index) in bars"
                :key="index"
                :x="bar.x"
                :y="bar.y"
                :width="bar.width"
                :height="bar.height"
                :fill="chartColor.stroke"
                rx="1"
                class="transition-all duration-500"
            />
        </g>
    </svg>
</template>
