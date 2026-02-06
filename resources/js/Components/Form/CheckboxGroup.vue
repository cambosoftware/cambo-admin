<script setup>
import { computed } from 'vue'
import Checkbox from './Checkbox.vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    options: {
        type: Array,
        default: () => []
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    },
    inline: {
        type: Boolean,
        default: false
    },
    optionLabel: {
        type: String,
        default: 'label'
    },
    optionValue: {
        type: String,
        default: 'value'
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const normalizedOptions = computed(() =>
    props.options.map(opt => {
        if (typeof opt === 'string' || typeof opt === 'number') {
            return { label: String(opt), value: opt }
        }
        return {
            label: opt[props.optionLabel] ?? opt.label ?? String(opt[props.optionValue]),
            value: opt[props.optionValue] ?? opt.value,
            description: opt.description ?? null,
            disabled: opt.disabled ?? false
        }
    })
)

const isChecked = (value) => props.modelValue.some(v => String(v) === String(value))

const toggleValue = (value) => {
    const current = [...props.modelValue]
    const idx = current.findIndex(v => String(v) === String(value))
    if (idx >= 0) {
        current.splice(idx, 1)
    } else {
        current.push(value)
    }
    emit('update:modelValue', current)
    emit('change', current)
}
</script>

<template>
    <div :class="inline ? 'flex flex-wrap gap-x-6 gap-y-2' : 'flex flex-col gap-2'">
        <Checkbox
            v-for="opt in normalizedOptions"
            :key="opt.value"
            :model-value="isChecked(opt.value)"
            :label="opt.label"
            :description="opt.description"
            :size="size"
            :disabled="disabled || opt.disabled"
            :error="error"
            @update:model-value="toggleValue(opt.value)"
        />
    </div>
</template>
