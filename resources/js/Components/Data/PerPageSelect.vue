<script setup>
defineProps({
    modelValue: {
        type: Number,
        required: true
    },
    options: {
        type: Array,
        default: () => [10, 25, 50, 100]
    },
    label: {
        type: String,
        default: 'par page'
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md'].includes(v)
    }
})

const emit = defineEmits(['update:modelValue'])

const handleChange = (event) => {
    emit('update:modelValue', parseInt(event.target.value))
}
</script>

<template>
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <span>Afficher</span>
        <select
            :value="modelValue"
            :class="[
                'rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500',
                size === 'sm' ? 'text-xs py-1 pr-7' : 'text-sm py-1.5 pr-8'
            ]"
            @change="handleChange"
        >
            <option v-for="option in options" :key="option" :value="option">
                {{ option }}
            </option>
        </select>
        <span>{{ label }}</span>
    </div>
</template>
