<script setup>
defineProps({
    title: {
        type: String,
        default: 'Aucun resultat'
    },
    description: {
        type: String,
        default: null
    },
    icon: {
        type: [Object, Function],
        default: null
    }
})
</script>

<template>
    <div class="text-center py-12 px-4">
        <!-- Icon -->
        <div class="mx-auto mb-4">
            <component
                v-if="icon"
                :is="icon"
                class="mx-auto h-12 w-12 text-gray-400"
            />
            <svg v-else class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        </div>

        <!-- Title -->
        <h3 class="text-sm font-semibold text-gray-900">{{ title }}</h3>

        <!-- Description -->
        <p v-if="description" class="mt-1 text-sm text-gray-500">{{ description }}</p>

        <!-- Actions -->
        <div v-if="$slots.action" class="mt-6">
            <slot name="action" />
        </div>
    </div>
</template>
