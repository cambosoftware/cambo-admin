<script setup>
import { inject, computed } from 'vue'
import { ChevronRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    item: {
        type: Object,
        required: true
    },
    level: {
        type: Number,
        default: 0
    }
})

const tree = inject('tree')

const hasChildren = computed(() => props.item.children && props.item.children.length > 0)
const isExpanded = computed(() => tree.isExpanded(props.item.id))
const isSelected = computed(() => tree.isSelected(props.item.id))
const isChecked = computed(() => tree.isChecked(props.item.id))
const paddingLeft = computed(() => `${props.level * tree.indent}px`)

const handleClick = () => {
    if (hasChildren.value) {
        tree.toggleExpand(props.item.id)
    }
    tree.selectNode(props.item.id)
}
</script>

<template>
    <div class="tree-node" role="treeitem" :aria-expanded="hasChildren ? isExpanded : undefined">
        <div
            :class="[
                'flex items-center gap-2 py-1.5 px-2 rounded-md cursor-pointer transition-colors',
                isSelected ? 'bg-indigo-100 text-indigo-700' : 'hover:bg-gray-100'
            ]"
            :style="{ paddingLeft }"
            @click="handleClick"
        >
            <!-- Expand/collapse icon -->
            <span class="w-4 h-4 flex-shrink-0">
                <ChevronRightIcon
                    v-if="hasChildren"
                    :class="[
                        'w-4 h-4 text-gray-400 transition-transform',
                        isExpanded ? 'rotate-90' : ''
                    ]"
                />
            </span>

            <!-- Checkbox -->
            <input
                v-if="tree.checkable"
                type="checkbox"
                :checked="isChecked"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                @click.stop
                @change="tree.toggleCheck(item.id)"
            />

            <!-- Icon -->
            <component
                v-if="item.icon"
                :is="item.icon"
                class="w-5 h-5 text-gray-400 flex-shrink-0"
            />

            <!-- Label -->
            <span class="text-sm truncate">
                {{ item.label }}
            </span>

            <!-- Badge/meta -->
            <slot name="extra" :item="item" />
        </div>

        <!-- Children -->
        <div v-if="hasChildren && isExpanded" role="group">
            <TreeNode
                v-for="child in item.children"
                :key="child.id"
                :item="child"
                :level="level + 1"
            />
        </div>
    </div>
</template>
