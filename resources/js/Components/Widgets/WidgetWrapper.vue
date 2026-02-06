<script setup>
import { computed } from 'vue'
import { XMarkIcon, Cog6ToothIcon, ArrowsPointingOutIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    widget: {
        type: Object,
        required: true
    },
    editMode: {
        type: Boolean,
        default: false
    },
    dragging: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['remove', 'configure', 'resize'])

const gridStyle = computed(() => ({
    gridColumn: `span ${props.widget.width}`,
    gridRow: `span ${props.widget.height}`,
}))
</script>

<template>
    <div
        :class="[
            'widget-wrapper relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all',
            editMode ? 'cursor-move ring-2 ring-primary-500/50' : '',
            dragging ? 'opacity-50 scale-95' : ''
        ]"
        :style="gridStyle"
    >
        <!-- Edit mode overlay -->
        <div
            v-if="editMode"
            class="absolute inset-0 bg-primary-500/5 z-10 flex items-center justify-center"
        >
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="p-2 bg-white dark:bg-gray-700 rounded-lg shadow-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                    title="Configurer"
                    @click.stop="emit('configure', widget)"
                >
                    <Cog6ToothIcon class="h-5 w-5 text-gray-600 dark:text-gray-300" />
                </button>
                <button
                    type="button"
                    class="p-2 bg-white dark:bg-gray-700 rounded-lg shadow-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                    title="Supprimer"
                    @click.stop="emit('remove', widget)"
                >
                    <XMarkIcon class="h-5 w-5 text-red-500" />
                </button>
            </div>
        </div>

        <!-- Resize handle (edit mode only) -->
        <div
            v-if="editMode"
            class="absolute bottom-0 right-0 w-6 h-6 cursor-se-resize z-20 flex items-center justify-center"
            @mousedown.stop="emit('resize', widget, $event)"
        >
            <ArrowsPointingOutIcon class="h-4 w-4 text-gray-400 rotate-90" />
        </div>

        <!-- Widget content -->
        <div :class="['h-full', editMode ? 'pointer-events-none' : '']">
            <slot />
        </div>
    </div>
</template>

<style scoped>
.widget-wrapper {
    min-height: 120px;
}
</style>
