<script setup>
import { computed } from 'vue'
import Radio from './Radio.vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, null],
        default: null
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

const onSelect = (val) => {
    emit('update:modelValue', val)
    emit('change', val)
}
</script>

<template>
    <div :class="inline ? 'flex flex-wrap gap-x-6 gap-y-2' : 'flex flex-col gap-2'" role="radiogroup">
        <Radio
            v-for="opt in normalizedOptions"
            :key="opt.value"
            :model-value="modelValue"
            :value="opt.value"
            :label="opt.label"
            :description="opt.description"
            :size="size"
            :disabled="disabled || opt.disabled"
            :error="error"
            @update:model-value="onSelect"
        />
    </div>
</template>
