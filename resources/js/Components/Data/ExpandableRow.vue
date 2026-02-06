<script setup>
import { ref } from 'vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    expanded: {
        type: Boolean,
        default: false
    },
    colspan: {
        type: Number,
        default: 1
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:expanded', 'toggle'])

const isExpanded = ref(props.expanded)

const toggle = () => {
    if (props.disabled) return
    isExpanded.value = !isExpanded.value
    emit('update:expanded', isExpanded.value)
    emit('toggle', isExpanded.value)
}
</script>

<template>
    <!-- Main row content slot wrapper -->
    <tr
        :class="[
            'transition-colors',
            !disabled ? 'cursor-pointer hover:bg-gray-50' : ''
        ]"
        @click="toggle"
    >
        <!-- Expand icon cell -->
        <td class="w-10 px-2 py-3">
            <button
                type="button"
                :disabled="disabled"
                class="p-1 rounded hover:bg-gray-200 disabled:opacity-50"
                @click.stop="toggle"
            >
                <ChevronDownIcon
                    :class="[
                        'h-5 w-5 text-gray-400 transition-transform',
                        isExpanded ? 'rotate-180' : ''
                    ]"
                />
            </button>
        </td>

        <!-- Row content -->
        <slot name="row" :expanded="isExpanded" :toggle="toggle" />
    </tr>

    <!-- Expanded content -->
    <tr v-show="isExpanded">
        <td :colspan="colspan + 1" class="bg-gray-50 px-6 py-4">
            <slot name="expanded" :expanded="isExpanded" />
        </td>
    </tr>
</template>
