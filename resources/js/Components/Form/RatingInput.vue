<script setup>
import { computed, ref } from 'vue'
import { StarIcon as StarSolid } from '@heroicons/vue/24/solid'
import { StarIcon as StarOutline } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: Number,
        default: 0
    },
    max: {
        type: Number,
        default: 5
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg', 'xl'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    readonly: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    color: {
        type: String,
        default: 'yellow',
        validator: (v) => ['yellow', 'orange', 'red', 'primary'].includes(v)
    },
    allowHalf: {
        type: Boolean,
        default: false
    },
    showValue: {
        type: Boolean,
        default: false
    },
    clearable: {
        type: Boolean,
        default: false
    },
    icon: {
        type: [Object, Function],
        default: null
    },
    iconOutline: {
        type: [Object, Function],
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const hoverValue = ref(0)
const hovering = ref(false)

const hasError = computed(() => !!props.error)

const isInteractive = computed(() => !props.disabled && !props.readonly)

const iconSize = computed(() => {
    const sizes = { sm: 'h-4 w-4', md: 'h-5 w-5', lg: 'h-6 w-6', xl: 'h-8 w-8' }
    return sizes[props.size]
})

const activeColor = computed(() => {
    const colors = {
        yellow: 'text-yellow-400',
        orange: 'text-orange-400',
        red: 'text-red-400',
        primary: 'text-primary-500'
    }
    return colors[props.color]
})

const currentValue = computed(() => {
    return hovering.value ? hoverValue.value : props.modelValue
})

const stars = computed(() => {
    return Array.from({ length: props.max }, (_, i) => {
        const starNum = i + 1
        const val = currentValue.value
        if (val >= starNum) return 'full'
        if (props.allowHalf && val >= starNum - 0.5) return 'half'
        return 'empty'
    })
})

const onMouseEnter = (index, e) => {
    if (!isInteractive.value) return
    hovering.value = true
    if (props.allowHalf) {
        const rect = e.target.getBoundingClientRect()
        const isLeftHalf = (e.clientX - rect.left) < rect.width / 2
        hoverValue.value = isLeftHalf ? index + 0.5 : index + 1
    } else {
        hoverValue.value = index + 1
    }
}

const onMouseMove = (index, e) => {
    if (!isInteractive.value || !props.allowHalf) return
    const rect = e.target.getBoundingClientRect()
    const isLeftHalf = (e.clientX - rect.left) < rect.width / 2
    hoverValue.value = isLeftHalf ? index + 0.5 : index + 1
}

const onMouseLeave = () => {
    hovering.value = false
    hoverValue.value = 0
}

const onClick = (index, e) => {
    if (!isInteractive.value) return
    let val
    if (props.allowHalf) {
        const rect = e.target.getBoundingClientRect()
        const isLeftHalf = (e.clientX - rect.left) < rect.width / 2
        val = isLeftHalf ? index + 0.5 : index + 1
    } else {
        val = index + 1
    }

    if (props.clearable && val === props.modelValue) {
        val = 0
    }

    emit('update:modelValue', val)
    emit('change', val)
}

const onKeydown = (e) => {
    if (!isInteractive.value) return
    if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
        e.preventDefault()
        const step = props.allowHalf ? 0.5 : 1
        const val = Math.min(props.modelValue + step, props.max)
        emit('update:modelValue', val)
        emit('change', val)
    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
        e.preventDefault()
        const step = props.allowHalf ? 0.5 : 1
        const val = Math.max(props.modelValue - step, 0)
        emit('update:modelValue', val)
        emit('change', val)
    }
}

const filledIcon = computed(() => props.icon || StarSolid)
const outlineIcon = computed(() => props.iconOutline || StarOutline)
</script>

<template>
    <div
        class="inline-flex items-center gap-0.5"
        :class="[
            disabled ? 'opacity-50' : '',
            isInteractive ? 'cursor-pointer' : ''
        ]"
        role="slider"
        :aria-valuemin="0"
        :aria-valuemax="max"
        :aria-valuenow="modelValue"
        tabindex="0"
        @mouseleave="onMouseLeave"
        @keydown="onKeydown"
    >
        <div
            v-for="(star, index) in stars"
            :key="index"
            class="relative"
            @mouseenter="onMouseEnter(index, $event)"
            @mousemove="onMouseMove(index, $event)"
            @click="onClick(index, $event)"
        >
            <!-- Half star -->
            <template v-if="star === 'half'">
                <component :is="outlineIcon" :class="[iconSize, 'text-gray-300']" />
                <div class="absolute inset-0 overflow-hidden" style="width: 50%">
                    <component :is="filledIcon" :class="[iconSize, hasError ? 'text-red-400' : activeColor]" />
                </div>
            </template>
            <!-- Full star -->
            <component
                v-else-if="star === 'full'"
                :is="filledIcon"
                :class="[iconSize, hasError ? 'text-red-400' : activeColor]"
            />
            <!-- Empty star -->
            <component
                v-else
                :is="outlineIcon"
                :class="[iconSize, 'text-gray-300', isInteractive ? 'hover:text-gray-400' : '']"
            />
        </div>

        <!-- Value display -->
        <span
            v-if="showValue"
            :class="[
                'ml-1.5 font-medium tabular-nums',
                size === 'sm' ? 'text-xs' : size === 'xl' ? 'text-lg' : 'text-sm',
                hasError ? 'text-red-600' : 'text-gray-600'
            ]"
        >
            {{ modelValue }}/{{ max }}
        </span>
    </div>
</template>
