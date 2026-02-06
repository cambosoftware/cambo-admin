<script setup>
import { computed } from 'vue'
import { TrashIcon, PencilIcon, ArchiveBoxIcon, ArrowDownTrayIcon } from '@heroicons/vue/20/solid'
import Button from '@/Components/UI/Button.vue'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'

const props = defineProps({
    selectedCount: {
        type: Number,
        required: true
    },
    totalCount: {
        type: Number,
        default: null
    },
    actions: {
        type: Array,
        default: () => []
        // [{ key: 'delete', label: 'Supprimer', icon: TrashIcon, variant: 'danger', confirm: true }]
    },
    loading: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['action', 'clear-selection', 'select-all'])

const defaultActions = [
    { key: 'delete', label: 'Supprimer', icon: TrashIcon, variant: 'danger', confirm: true },
    { key: 'export', label: 'Exporter', icon: ArrowDownTrayIcon },
    { key: 'archive', label: 'Archiver', icon: ArchiveBoxIcon }
]

const computedActions = computed(() => {
    return props.actions.length > 0 ? props.actions : defaultActions
})

const handleAction = (action) => {
    emit('action', action.key)
}
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-1"
    >
        <div
            v-if="selectedCount > 0"
            class="flex items-center gap-3 px-4 py-3 bg-primary-50 border border-primary-200 rounded-lg"
        >
            <!-- Selection info -->
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 text-xs font-medium bg-primary-500 text-white rounded-full">
                    {{ selectedCount }}
                </span>
                <span class="text-sm text-primary-700">
                    élément{{ selectedCount > 1 ? 's' : '' }} sélectionné{{ selectedCount > 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Select all link -->
            <button
                v-if="totalCount && selectedCount < totalCount"
                type="button"
                class="text-sm text-primary-600 hover:text-primary-800 underline"
                @click="$emit('select-all')"
            >
                Tout sélectionner ({{ totalCount }})
            </button>

            <div class="flex-1" />

            <!-- Quick actions -->
            <div class="flex items-center gap-2">
                <template v-for="action in computedActions.slice(0, 3)" :key="action.key">
                    <Button
                        size="sm"
                        :variant="action.variant || 'secondary'"
                        :icon="action.icon"
                        :loading="loading"
                        @click="handleAction(action)"
                    >
                        {{ action.label }}
                    </Button>
                </template>

                <!-- More actions dropdown -->
                <Dropdown v-if="computedActions.length > 3" align="right">
                    <template #trigger>
                        <Button size="sm" variant="secondary">
                            Plus...
                        </Button>
                    </template>
                    <div class="py-1">
                        <DropdownItem
                            v-for="action in computedActions.slice(3)"
                            :key="action.key"
                            :icon="action.icon"
                            @click="handleAction(action)"
                        >
                            {{ action.label }}
                        </DropdownItem>
                    </div>
                </Dropdown>
            </div>

            <!-- Clear selection -->
            <button
                type="button"
                class="text-sm text-gray-500 hover:text-gray-700"
                @click="$emit('clear-selection')"
            >
                Annuler
            </button>
        </div>
    </transition>
</template>
