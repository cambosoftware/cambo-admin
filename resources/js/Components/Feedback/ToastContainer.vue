<script setup>
import { computed } from 'vue'

const props = defineProps({
    position: {
        type: String,
        default: 'top-right',
        validator: (v) => ['top-right', 'top-left', 'bottom-right', 'bottom-left', 'top-center', 'bottom-center'].includes(v)
    }
})

const positionClasses = computed(() => {
    const positions = {
        'top-right': 'top-4 right-4 items-end',
        'top-left': 'top-4 left-4 items-start',
        'bottom-right': 'bottom-4 right-4 items-end',
        'bottom-left': 'bottom-4 left-4 items-start',
        'top-center': 'top-4 left-1/2 -translate-x-1/2 items-center',
        'bottom-center': 'bottom-4 left-1/2 -translate-x-1/2 items-center'
    }
    return positions[props.position]
})
</script>

<template>
    <Teleport to="body">
        <div :class="['fixed z-[100] flex flex-col gap-3 pointer-events-none', positionClasses]">
            <div class="pointer-events-auto">
                <slot />
            </div>
        </div>
    </Teleport>
</template>
