<script setup>
defineProps({
    items: {
        type: Array,
        default: () => []
        // [{ label: 'Name', value: 'John Doe' }, ...]
    },
    columns: {
        type: Number,
        default: 1,
        validator: (v) => [1, 2, 3, 4].includes(v)
    },
    striped: {
        type: Boolean,
        default: false
    },
    bordered: {
        type: Boolean,
        default: false
    },
    horizontal: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <dl
        :class="[
            bordered ? 'border border-gray-200 rounded-lg overflow-hidden' : '',
            horizontal ? '' : 'grid',
            columns === 1 ? 'grid-cols-1' : '',
            columns === 2 ? 'grid-cols-1 sm:grid-cols-2' : '',
            columns === 3 ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' : '',
            columns === 4 ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' : '',
            !horizontal && striped ? 'divide-y divide-gray-200' : ''
        ]"
    >
        <template v-if="items.length > 0">
            <div
                v-for="(item, index) in items"
                :key="item.label || index"
                :class="[
                    horizontal ? 'flex items-baseline gap-4 border-b border-gray-200 last:border-b-0' : '',
                    compact ? 'px-3 py-2' : 'px-4 py-4',
                    striped && !horizontal && index % 2 === 1 ? 'bg-gray-50' : 'bg-white'
                ]"
            >
                <dt
                    :class="[
                        'text-sm font-medium text-gray-500',
                        horizontal ? 'w-1/3 flex-shrink-0' : ''
                    ]"
                >
                    {{ item.label }}
                </dt>
                <dd
                    :class="[
                        'text-sm text-gray-900',
                        horizontal ? 'flex-1' : 'mt-1'
                    ]"
                >
                    <slot :name="`item-${item.key || index}`" :item="item">
                        {{ item.value }}
                    </slot>
                </dd>
            </div>
        </template>

        <!-- Custom items via slot -->
        <slot v-else />
    </dl>
</template>
