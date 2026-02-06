<script setup>
import { inject, computed, ref } from 'vue'

const props = defineProps({
    label: {
        type: String,
        default: null
    },
    theme: {
        type: String,
        default: null
    }
})

// Inject sidebar state from parent Sidebar component
const sidebarCollapsed = inject('sidebarCollapsed', false)
const sidebarTheme = inject('sidebarTheme', 'dark')
const sidebarPosition = inject('sidebarPosition', ref('left'))

// Check if horizontal mode (top position)
const isHorizontal = computed(() => sidebarPosition.value === 'top')

// Use prop theme if provided, otherwise use injected theme
const currentTheme = computed(() => props.theme || sidebarTheme.value || 'dark')
</script>

<template>
    <!-- Horizontal mode: show as vertical separator line -->
    <div
        v-if="isHorizontal"
        class="h-6 w-px mx-2 self-center"
        :class="currentTheme === 'dark' ? 'bg-gray-700' : 'bg-gray-300'"
    />

    <!-- Vertical mode: original layout -->
    <div v-else class="my-4">
        <!-- Container with fixed height to prevent layout shift -->
        <div v-if="label" class="h-6 flex items-center" :class="sidebarCollapsed ? 'justify-center' : 'px-3'">
            <!-- When collapsed: show a centered short line -->
            <hr
                v-if="sidebarCollapsed"
                class="w-6"
                :class="currentTheme === 'dark' ? 'border-gray-700' : 'border-gray-300'"
            />
            <!-- When expanded: show the label -->
            <span
                v-else
                class="text-xs font-semibold uppercase tracking-wider"
                :class="currentTheme === 'dark' ? 'text-gray-500' : 'text-gray-400'"
            >
                {{ label }}
            </span>
        </div>
        <!-- When no label: show full-width line -->
        <hr
            v-else
            :class="currentTheme === 'dark' ? 'border-gray-800' : 'border-gray-200'"
        />
    </div>
</template>
