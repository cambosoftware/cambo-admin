<script setup>
import { CubeIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    config: {
        type: Object,
        default: () => ({})
    }
})
</script>

<template>
    <div class="h-full flex flex-col items-center justify-center p-4 text-gray-400">
        <CubeIcon class="h-12 w-12 mb-2" />
        <p class="text-sm text-center">
            Widget non disponible
        </p>
        <p class="text-xs text-gray-500 mt-1">
            {{ config.title || 'Configuration requise' }}
        </p>
    </div>
</template>
