<script setup>
import { computed } from 'vue'

const props = defineProps({
    header: {
        type: Boolean,
        default: false
    },
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'center', 'right'].includes(v)
    },
    width: {
        type: String,
        default: null
    },
    compact: {
        type: Boolean,
        default: false
    },
    nowrap: {
        type: Boolean,
        default: false
    },
    colspan: {
        type: [Number, String],
        default: null
    },
    rowspan: {
        type: [Number, String],
        default: null
    }
})

const alignClass = computed(() => {
    const aligns = {
        left: 'text-left',
        center: 'text-center',
        right: 'text-right'
    }
    return aligns[props.align]
})

const paddingClass = computed(() => {
    return props.compact ? 'px-3 py-2' : 'px-4 py-3'
})
</script>

<template>
    <th
        v-if="header"
        :class="[
            'text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider',
            paddingClass,
            alignClass,
            nowrap ? 'whitespace-nowrap' : ''
        ]"
        :style="width ? { width } : {}"
        :colspan="colspan"
        :rowspan="rowspan"
    >
        <slot />
    </th>
    <td
        v-else
        :class="[
            'text-sm text-gray-900 dark:text-gray-100',
            paddingClass,
            alignClass,
            nowrap ? 'whitespace-nowrap' : ''
        ]"
        :style="width ? { width } : {}"
        :colspan="colspan"
        :rowspan="rowspan"
    >
        <slot />
    </td>
</template>
