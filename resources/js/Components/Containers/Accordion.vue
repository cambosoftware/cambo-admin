<script setup>
import { provide, ref } from 'vue'

const props = defineProps({
    multiple: {
        type: Boolean,
        default: false
    },
    bordered: {
        type: Boolean,
        default: true
    }
})

const openItems = ref(new Set())

const toggle = (id) => {
    if (props.multiple) {
        if (openItems.value.has(id)) {
            openItems.value.delete(id)
        } else {
            openItems.value.add(id)
        }
    } else {
        if (openItems.value.has(id)) {
            openItems.value.clear()
        } else {
            openItems.value.clear()
            openItems.value.add(id)
        }
    }
    // Force reactivity
    openItems.value = new Set(openItems.value)
}

provide('accordion', { openItems, toggle })
</script>

<template>
    <div
        :class="[
            'divide-y divide-gray-200',
            bordered ? 'border border-gray-200 rounded-xl overflow-hidden' : ''
        ]"
    >
        <slot />
    </div>
</template>
