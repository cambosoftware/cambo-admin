<script setup>
defineProps({
    label: {
        type: String,
        default: null
    },
    variant: {
        type: String,
        default: 'horizontal',
        validator: (v) => ['horizontal', 'vertical'].includes(v)
    },
    spacing: {
        type: String,
        default: 'normal',
        validator: (v) => ['tight', 'normal', 'loose'].includes(v)
    }
})

const spacingClasses = {
    horizontal: {
        tight: 'gap-1',
        normal: 'gap-2',
        loose: 'gap-4'
    },
    vertical: {
        tight: 'gap-0.5',
        normal: 'gap-1',
        loose: 'gap-2'
    }
}
</script>

<template>
    <nav :aria-label="label">
        <div
            :class="[
                'flex',
                variant === 'vertical' ? 'flex-col' : 'flex-row items-center',
                spacingClasses[variant][spacing]
            ]"
        >
            <slot />
        </div>
    </nav>
</template>
