<script setup>
import { computed } from 'vue'
import { EyeIcon, PencilIcon, TrashIcon, EllipsisVerticalIcon } from '@heroicons/vue/20/solid'
import IconButton from '@/Components/UI/IconButton.vue'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import DropdownDivider from '@/Components/Overlays/DropdownDivider.vue'

const props = defineProps({
    actions: {
        type: Array,
        default: () => []
        // [{ key: 'view', label: 'Voir', icon: EyeIcon, href: '/items/1', variant: 'ghost' }]
    },
    row: {
        type: Object,
        default: null
    },
    maxVisible: {
        type: Number,
        default: 3
    },
    size: {
        type: String,
        default: 'sm',
        validator: (v) => ['xs', 'sm', 'md'].includes(v)
    },
    dropdown: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['action'])

const defaultIcons = {
    view: EyeIcon,
    edit: PencilIcon,
    delete: TrashIcon
}

const visibleActions = computed(() => {
    if (props.dropdown) return []
    return props.actions.slice(0, props.maxVisible)
})

const menuActions = computed(() => {
    if (props.dropdown) return props.actions
    return props.actions.slice(props.maxVisible)
})

const hasMenuActions = computed(() => menuActions.value.length > 0)

const getIcon = (action) => {
    return action.icon || defaultIcons[action.key] || EyeIcon
}

const handleAction = (action) => {
    emit('action', { key: action.key, row: props.row, action })
}
</script>

<template>
    <div class="flex items-center gap-1">
        <!-- Visible actions as icon buttons -->
        <IconButton
            v-for="action in visibleActions"
            :key="action.key"
            :icon="getIcon(action)"
            :variant="action.variant || 'ghost'"
            :size="size"
            :label="action.label"
            :href="action.href"
            :disabled="action.disabled"
            @click="!action.href && handleAction(action)"
        />

        <!-- Dropdown for more actions -->
        <Dropdown v-if="hasMenuActions || dropdown" align="right">
            <template #trigger>
                <IconButton
                    :icon="EllipsisVerticalIcon"
                    variant="ghost"
                    :size="size"
                    label="Plus d'actions"
                />
            </template>
            <div class="py-1 min-w-40">
                <template v-for="(action, index) in menuActions" :key="action.key">
                    <DropdownDivider v-if="action.divider" />
                    <DropdownItem
                        v-else
                        :icon="getIcon(action)"
                        :href="action.href"
                        :disabled="action.disabled"
                        :variant="action.variant === 'danger' ? 'danger' : 'default'"
                        @click="!action.href && handleAction(action)"
                    >
                        {{ action.label }}
                    </DropdownItem>
                </template>
            </div>
        </Dropdown>
    </div>
</template>
