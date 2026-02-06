<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { SparklesIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    config: {
        type: Object,
        default: () => ({})
    }
})

const page = usePage()
const user = computed(() => page.props.auth?.user)

const greeting = computed(() => {
    const hour = new Date().getHours()
    if (hour < 12) return 'Bonjour'
    if (hour < 18) return 'Bon après-midi'
    return 'Bonsoir'
})
</script>

<template>
    <div class="h-full flex items-center px-6 bg-gradient-to-r from-primary-500 to-primary-600 text-white">
        <div class="flex-1">
            <div class="flex items-center gap-2 text-primary-100 mb-1">
                <SparklesIcon class="h-5 w-5" />
                <span class="text-sm font-medium">{{ greeting }}</span>
            </div>
            <h2 class="text-2xl font-bold mb-1">
                {{ user?.name || 'Utilisateur' }}
            </h2>
            <p class="text-primary-100 text-sm">
                {{ config.message || 'Bienvenue sur votre tableau de bord.' }}
            </p>
        </div>

        <div class="hidden sm:flex items-center gap-4 text-primary-100">
            <div class="text-right">
                <div class="text-3xl font-bold text-white">
                    {{ new Date().toLocaleDateString('fr-FR', { day: 'numeric' }) }}
                </div>
                <div class="text-sm">
                    {{ new Date().toLocaleDateString('fr-FR', { weekday: 'long', month: 'long' }) }}
                </div>
            </div>
        </div>
    </div>
</template>
