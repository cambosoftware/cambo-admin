<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    horizontal: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    },
    span: {
        type: Number,
        default: 1,
        validator: (v) => [1, 2, 3, 4].includes(v)
    }
})
</script>

<template>
    <div
        :class="[
            horizontal ? 'flex items-baseline gap-4 border-b border-gray-200 last:border-b-0' : '',
            compact ? 'px-3 py-2' : 'px-4 py-4',
            span === 2 ? 'sm:col-span-2' : '',
            span === 3 ? 'sm:col-span-2 lg:col-span-3' : '',
            span === 4 ? 'sm:col-span-2 lg:col-span-4' : ''
        ]"
    >
        <dt
            :class="[
                'text-sm font-medium text-gray-500',
                horizontal ? 'w-1/3 flex-shrink-0' : ''
            ]"
        >
            {{ label }}
        </dt>
        <dd
            :class="[
                'text-sm text-gray-900',
                horizontal ? 'flex-1' : 'mt-1'
            ]"
        >
            <slot />
        </dd>
    </div>
</template>
