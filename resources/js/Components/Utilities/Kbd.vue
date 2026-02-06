<script setup>
defineProps({
    keys: {
        type: Array,
        default: null
        // ['Ctrl', 'S'] or just use slot
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md'].includes(v)
    }
})

const sizeClasses = {
    sm: 'px-1 py-0.5 text-xs min-w-4',
    md: 'px-1.5 py-0.5 text-xs min-w-5'
}
</script>

<template>
    <span class="inline-flex items-center gap-0.5">
        <template v-if="keys">
            <template v-for="(key, index) in keys" :key="index">
                <kbd
                    :class="[
                        'inline-flex items-center justify-center font-sans font-medium',
                        'bg-gray-100 text-gray-600 rounded border border-gray-300',
                        'shadow-sm',
                        sizeClasses[size]
                    ]"
                >
                    {{ key }}
                </kbd>
                <span v-if="index < keys.length - 1" class="text-gray-400 text-xs">+</span>
            </template>
        </template>
        <kbd
            v-else
            :class="[
                'inline-flex items-center justify-center font-sans font-medium',
                'bg-gray-100 text-gray-600 rounded border border-gray-300',
                'shadow-sm',
                sizeClasses[size]
            ]"
        >
            <slot />
        </kbd>
    </span>
</template>
