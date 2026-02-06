<script setup>
import { computed } from 'vue'

const props = defineProps({
    clickable: {
        type: Boolean,
        default: false
    },
    href: {
        type: String,
        default: null
    },
    selected: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    },
    hoverable: {
        type: Boolean,
        default: null
    }
})

defineEmits(['click'])

const isHoverable = computed(() => {
    if (props.hoverable !== null) return props.hoverable
    return props.clickable || props.href
})

const paddingClass = computed(() => {
    return props.compact ? 'px-3 py-2' : 'px-4 py-3'
})
</script>

<template>
    <li
        :class="[
            'relative',
            paddingClass,
            selected ? 'bg-primary-50' : '',
            isHoverable && !disabled ? 'hover:bg-gray-50 cursor-pointer' : '',
            disabled ? 'opacity-50 pointer-events-none' : ''
        ]"
        @click="!disabled && $emit('click', $event)"
    >
        <component
            :is="href ? 'a' : 'div'"
            :href="href"
            class="flex items-center gap-3"
        >
            <!-- Leading slot (icon/avatar) -->
            <div v-if="$slots.leading" class="flex-shrink-0">
                <slot name="leading" />
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <slot />
            </div>

            <!-- Trailing slot (actions/meta) -->
            <div v-if="$slots.trailing" class="flex-shrink-0">
                <slot name="trailing" />
            </div>
        </component>
    </li>
</template>
