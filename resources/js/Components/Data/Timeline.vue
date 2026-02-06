<script setup>
defineProps({
    items: {
        type: Array,
        default: () => []
        // [{ id, title, description?, date?, icon?, variant? }]
    },
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'right', 'alternate'].includes(v)
    },
    lineColor: {
        type: String,
        default: 'gray'
    }
})
</script>

<template>
    <div class="relative">
        <!-- Timeline line -->
        <div
            v-if="align !== 'alternate'"
            :class="[
                'absolute top-0 bottom-0 w-0.5 bg-gray-200',
                align === 'left' ? 'left-4' : 'right-4'
            ]"
        />

        <!-- Items -->
        <div class="space-y-6">
            <slot :items="items">
                <TimelineItem
                    v-for="(item, index) in items"
                    :key="item.id || index"
                    :title="item.title"
                    :description="item.description"
                    :date="item.date"
                    :icon="item.icon"
                    :variant="item.variant"
                    :align="align === 'alternate' ? (index % 2 === 0 ? 'left' : 'right') : align"
                />
            </slot>
        </div>
    </div>
</template>

<script>
import TimelineItem from './TimelineItem.vue'

export default {
    components: { TimelineItem }
}
</script>
