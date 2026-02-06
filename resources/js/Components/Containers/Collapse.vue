<script setup>
import { ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: null
    },
    defaultOpen: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const internalOpen = ref(props.defaultOpen)

const isOpen = props.modelValue !== null
    ? { get: () => props.modelValue, set: (v) => emit('update:modelValue', v) }
    : { get: () => internalOpen.value, set: (v) => internalOpen.value = v }

const toggle = () => {
    isOpen.set(!isOpen.get())
}
</script>

<template>
    <div>
        <!-- Trigger -->
        <slot name="trigger" :open="isOpen.get()" :toggle="toggle" />

        <!-- Content -->
        <Transition
            enter-active-class="transition-all duration-200 ease-out overflow-hidden"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-screen opacity-100"
            leave-active-class="transition-all duration-150 ease-in overflow-hidden"
            leave-from-class="max-h-screen opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <div v-if="isOpen.get()">
                <slot />
            </div>
        </Transition>
    </div>
</template>
