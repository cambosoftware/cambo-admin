<script setup>
defineProps({
    label: {
        type: String,
        default: null
    },
    orientation: {
        type: String,
        default: 'horizontal',
        validator: (v) => ['horizontal', 'vertical'].includes(v)
    },
    dashed: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <!-- Vertical -->
    <div
        v-if="orientation === 'vertical'"
        :class="[
            'self-stretch w-px mx-2',
            dashed ? 'border-l border-dashed border-gray-300' : 'bg-gray-200'
        ]"
    />

    <!-- Horizontal with label -->
    <div
        v-else-if="label"
        class="relative my-4"
    >
        <div class="absolute inset-0 flex items-center">
            <div
                :class="[
                    'w-full',
                    dashed ? 'border-t border-dashed border-gray-300' : 'border-t border-gray-200'
                ]"
            />
        </div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-sm text-gray-500">{{ label }}</span>
        </div>
    </div>

    <!-- Horizontal simple -->
    <hr
        v-else
        :class="[
            'my-4',
            dashed ? 'border-dashed border-gray-300' : 'border-gray-200'
        ]"
    />
</template>
