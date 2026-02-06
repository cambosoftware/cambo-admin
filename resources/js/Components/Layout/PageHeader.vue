<script setup>
import Breadcrumb from './Breadcrumb.vue'

defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: null
    },
    breadcrumb: {
        type: Array,
        default: () => []
    },
    showBreadcrumb: {
        type: Boolean,
        default: true
    },
    border: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div
        class="mb-6"
        :class="border ? 'pb-6 border-b border-gray-200' : ''"
    >
        <!-- Breadcrumb -->
        <Breadcrumb
            v-if="showBreadcrumb && breadcrumb.length > 0"
            :items="breadcrumb"
            class="mb-4"
        />

        <!-- Header content -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Title & Subtitle -->
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-gray-900 truncate">
                    {{ title }}
                </h1>
                <p
                    v-if="subtitle"
                    class="mt-1 text-sm text-gray-500"
                >
                    {{ subtitle }}
                </p>
                <!-- Description slot -->
                <slot name="description" />
            </div>

            <!-- Actions -->
            <div
                v-if="$slots.actions"
                class="flex flex-shrink-0 items-center gap-3"
            >
                <slot name="actions" />
            </div>
        </div>

        <!-- Tabs or additional content -->
        <div v-if="$slots.tabs" class="mt-4">
            <slot name="tabs" />
        </div>
    </div>
</template>
