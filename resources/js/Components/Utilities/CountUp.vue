<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
    value: {
        type: Number,
        required: true
    },
    duration: {
        type: Number,
        default: 1000
    },
    delay: {
        type: Number,
        default: 0
    },
    decimals: {
        type: Number,
        default: 0
    },
    separator: {
        type: String,
        default: ' '
    },
    prefix: {
        type: String,
        default: ''
    },
    suffix: {
        type: String,
        default: ''
    },
    easing: {
        type: String,
        default: 'easeOutQuart',
        validator: (v) => ['linear', 'easeOutQuart', 'easeOutExpo'].includes(v)
    }
})

const displayValue = ref(0)
let animationFrame = null
let startTime = null
let startValue = 0

const easingFunctions = {
    linear: (t) => t,
    easeOutQuart: (t) => 1 - Math.pow(1 - t, 4),
    easeOutExpo: (t) => t === 1 ? 1 : 1 - Math.pow(2, -10 * t)
}

const formatNumber = (num) => {
    const fixed = num.toFixed(props.decimals)
    const [intPart, decPart] = fixed.split('.')
    const formatted = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, props.separator)
    return decPart ? `${formatted}.${decPart}` : formatted
}

const animate = (timestamp) => {
    if (!startTime) startTime = timestamp
    const elapsed = timestamp - startTime
    const progress = Math.min(elapsed / props.duration, 1)
    const easedProgress = easingFunctions[props.easing](progress)

    displayValue.value = startValue + (props.value - startValue) * easedProgress

    if (progress < 1) {
        animationFrame = requestAnimationFrame(animate)
    } else {
        displayValue.value = props.value
    }
}

const startAnimation = () => {
    if (animationFrame) {
        cancelAnimationFrame(animationFrame)
    }
    startTime = null
    startValue = displayValue.value
    setTimeout(() => {
        animationFrame = requestAnimationFrame(animate)
    }, props.delay)
}

onMounted(() => {
    startAnimation()
})

watch(() => props.value, () => {
    startAnimation()
})
</script>

<template>
    <span class="tabular-nums">
        {{ prefix }}{{ formatNumber(displayValue) }}{{ suffix }}
    </span>
</template>
