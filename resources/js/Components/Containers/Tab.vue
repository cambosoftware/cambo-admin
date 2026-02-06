<script setup>
import { inject, computed, onMounted, onUnmounted, getCurrentInstance } from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    id: {
        type: [String, Number],
        default: null
    },
    icon: {
        type: [Object, Function],
        default: null
    },
    badge: {
        type: [String, Number],
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const instance = getCurrentInstance()
const tabId = props.id ?? instance.uid

const { activeTab, registerTab, unregisterTab } = inject('tabs')

const isActive = computed(() => activeTab.value === tabId)

onMounted(() => {
    registerTab({ id: tabId, label: props.label, icon: props.icon, badge: props.badge, disabled: props.disabled })
})

onUnmounted(() => {
    unregisterTab(tabId)
})
</script>

<template>
    <div v-show="isActive" role="tabpanel">
        <slot />
    </div>
</template>
