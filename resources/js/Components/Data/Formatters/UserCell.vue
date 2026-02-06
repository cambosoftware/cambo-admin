<script setup>
import Avatar from '@/Components/UI/Avatar.vue'

const props = defineProps({
    value: {
        type: Object,
        default: null
        // { name, email?, avatar?, src?, image? }
    },
    name: {
        type: String,
        default: null
    },
    email: {
        type: String,
        default: null
    },
    avatar: {
        type: String,
        default: null
    },
    size: {
        type: String,
        default: 'sm',
        validator: (v) => ['xs', 'sm', 'md'].includes(v)
    },
    showEmail: {
        type: Boolean,
        default: true
    },
    emptyText: {
        type: String,
        default: '-'
    }
})

const userName = props.name || props.value?.name
const userEmail = props.email || props.value?.email
const userAvatar = props.avatar || props.value?.avatar || props.value?.src || props.value?.image
</script>

<template>
    <span v-if="!userName" class="text-gray-400">
        {{ emptyText }}
    </span>

    <div v-else class="flex items-center gap-2">
        <Avatar
            :src="userAvatar"
            :name="userName"
            :size="size"
        />
        <div class="min-w-0">
            <div class="text-sm font-medium text-gray-900 truncate">
                {{ userName }}
            </div>
            <div
                v-if="showEmail && userEmail"
                class="text-xs text-gray-500 truncate"
            >
                {{ userEmail }}
            </div>
        </div>
    </div>
</template>
