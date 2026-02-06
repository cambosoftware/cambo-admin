<script setup>
import { provide, ref } from 'vue'

const props = defineProps({
    items: {
        type: Array,
        default: () => []
        // [{ id, label, icon?, children?: [], expanded?: boolean }]
    },
    expandedKeys: {
        type: Array,
        default: () => []
    },
    selectedKey: {
        type: [String, Number],
        default: null
    },
    selectable: {
        type: Boolean,
        default: false
    },
    checkable: {
        type: Boolean,
        default: false
    },
    checkedKeys: {
        type: Array,
        default: () => []
    },
    defaultExpandAll: {
        type: Boolean,
        default: false
    },
    indent: {
        type: Number,
        default: 24
    }
})

const emit = defineEmits(['update:expandedKeys', 'update:selectedKey', 'update:checkedKeys', 'select', 'check', 'expand'])

const internalExpandedKeys = ref([...props.expandedKeys])
const internalSelectedKey = ref(props.selectedKey)
const internalCheckedKeys = ref([...props.checkedKeys])

const toggleExpand = (key) => {
    const index = internalExpandedKeys.value.indexOf(key)
    if (index === -1) {
        internalExpandedKeys.value.push(key)
    } else {
        internalExpandedKeys.value.splice(index, 1)
    }
    emit('update:expandedKeys', internalExpandedKeys.value)
    emit('expand', key, index === -1)
}

const selectNode = (key) => {
    if (!props.selectable) return
    internalSelectedKey.value = key
    emit('update:selectedKey', key)
    emit('select', key)
}

const toggleCheck = (key) => {
    if (!props.checkable) return
    const index = internalCheckedKeys.value.indexOf(key)
    if (index === -1) {
        internalCheckedKeys.value.push(key)
    } else {
        internalCheckedKeys.value.splice(index, 1)
    }
    emit('update:checkedKeys', internalCheckedKeys.value)
    emit('check', key, index === -1)
}

const isExpanded = (key) => internalExpandedKeys.value.includes(key)
const isSelected = (key) => internalSelectedKey.value === key
const isChecked = (key) => internalCheckedKeys.value.includes(key)

provide('tree', {
    indent: props.indent,
    selectable: props.selectable,
    checkable: props.checkable,
    toggleExpand,
    selectNode,
    toggleCheck,
    isExpanded,
    isSelected,
    isChecked
})
</script>

<template>
    <div class="tree" role="tree">
        <slot :items="items">
            <TreeNode
                v-for="item in items"
                :key="item.id"
                :item="item"
                :level="0"
            />
        </slot>
    </div>
</template>

<script>
import TreeNode from './TreeNode.vue'

export default {
    components: { TreeNode }
}
</script>
