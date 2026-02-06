<script setup>
import { computed, useSlots } from 'vue'

const props = defineProps({
    label: {
        type: String,
        default: null
    },
    for: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    },
    error: {
        type: String,
        default: null
    },
    hint: {
        type: String,
        default: null
    },
    inline: {
        type: Boolean,
        default: false
    }
})

const slots = useSlots()
</script>

<template>
    <div :class="inline ? 'sm:flex sm:items-start sm:gap-4' : ''">
        <!-- Label -->
        <label
            v-if="label"
            :for="$props.for"
            :class="[
                'block text-sm font-medium text-gray-700',
                inline ? 'sm:w-1/3 sm:pt-2 sm:text-right' : 'mb-1'
            ]"
        >
            {{ label }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <!-- Input area -->
        <div :class="inline ? 'sm:flex-1' : ''">
            <slot />

            <!-- Error message -->
            <p v-if="error" class="mt-1 text-sm text-red-600">
                {{ error }}
            </p>

            <!-- Hint -->
            <p v-else-if="hint" class="mt-1 text-sm text-gray-500">
                {{ hint }}
            </p>
        </div>
    </div>
</template>
