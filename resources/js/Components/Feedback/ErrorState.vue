<script setup>
defineProps({
    title: {
        type: String,
        default: 'Une erreur est survenue'
    },
    description: {
        type: String,
        default: 'Veuillez reessayer ou contacter le support si le probleme persiste.'
    },
    retryLabel: {
        type: String,
        default: 'Reessayer'
    },
    showRetry: {
        type: Boolean,
        default: true
    }
})

defineEmits(['retry'])
</script>

<template>
    <div class="text-center py-12 px-4">
        <!-- Icon -->
        <div class="mx-auto mb-4">
            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <!-- Title -->
        <h3 class="text-sm font-semibold text-gray-900">{{ title }}</h3>

        <!-- Description -->
        <p v-if="description" class="mt-1 text-sm text-gray-500">{{ description }}</p>

        <!-- Retry -->
        <div v-if="showRetry" class="mt-6">
            <slot name="action">
                <button
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors cursor-pointer"
                    @click="$emit('retry')"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                    </svg>
                    {{ retryLabel }}
                </button>
            </slot>
        </div>
    </div>
</template>
