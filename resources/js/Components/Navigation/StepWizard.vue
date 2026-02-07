<script setup>
import { computed, provide, ref } from 'vue'
import { CheckIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    steps: {
        type: Array,
        required: true
        // [{ id: 'step1', label: 'Step 1', description?: 'Description' }]
    },
    currentStep: {
        type: [String, Number],
        default: null
    },
    variant: {
        type: String,
        default: 'horizontal',
        validator: (v) => ['horizontal', 'vertical', 'simple'].includes(v)
    },
    clickable: {
        type: Boolean,
        default: false
    },
    showNumbers: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['update:currentStep', 'step-click'])

const currentIndex = computed(() => {
    if (props.currentStep === null) return 0
    const idx = props.steps.findIndex(s => s.id === props.currentStep || s === props.currentStep)
    return idx >= 0 ? idx : 0
})

const getStepStatus = (index) => {
    if (index < currentIndex.value) return 'completed'
    if (index === currentIndex.value) return 'current'
    return 'upcoming'
}

const handleStepClick = (step, index) => {
    if (!props.clickable) return
    if (index > currentIndex.value) return // Can't skip ahead
    emit('update:currentStep', step.id || step)
    emit('step-click', { step, index })
}

provide('stepWizard', {
    currentStep: computed(() => props.currentStep),
    currentIndex,
    getStepStatus
})
</script>

<template>
    <!-- Simple variant (just dots) -->
    <nav v-if="variant === 'simple'" class="flex items-center justify-center gap-2">
        <button
            v-for="(step, index) in steps"
            :key="step.id || index"
            type="button"
            :disabled="!clickable || index > currentIndex"
            :class="[
                'h-2.5 w-2.5 rounded-full transition-colors',
                getStepStatus(index) === 'current' ? 'bg-indigo-500' : '',
                getStepStatus(index) === 'completed' ? 'bg-indigo-300' : '',
                getStepStatus(index) === 'upcoming' ? 'bg-gray-200' : '',
                clickable && index <= currentIndex ? 'cursor-pointer hover:bg-indigo-400' : ''
            ]"
            :aria-current="getStepStatus(index) === 'current' ? 'step' : undefined"
            @click="handleStepClick(step, index)"
        />
    </nav>

    <!-- Horizontal variant -->
    <nav v-else-if="variant === 'horizontal'" class="w-full">
        <ol class="flex items-center">
            <li
                v-for="(step, index) in steps"
                :key="step.id || index"
                :class="[
                    'flex items-center',
                    index < steps.length - 1 ? 'flex-1' : ''
                ]"
            >
                <button
                    type="button"
                    :disabled="!clickable || index > currentIndex"
                    class="group flex items-center"
                    :class="clickable && index <= currentIndex ? 'cursor-pointer' : 'cursor-default'"
                    @click="handleStepClick(step, index)"
                >
                    <!-- Step indicator -->
                    <span
                        :class="[
                            'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 transition-colors',
                            getStepStatus(index) === 'completed' ? 'bg-indigo-500 border-indigo-500' : '',
                            getStepStatus(index) === 'current' ? 'border-indigo-500 bg-white' : '',
                            getStepStatus(index) === 'upcoming' ? 'border-gray-300 bg-white' : ''
                        ]"
                    >
                        <CheckIcon
                            v-if="getStepStatus(index) === 'completed'"
                            class="h-5 w-5 text-white"
                        />
                        <span
                            v-else-if="showNumbers"
                            :class="[
                                'text-sm font-medium',
                                getStepStatus(index) === 'current' ? 'text-indigo-500' : 'text-gray-500'
                            ]"
                        >
                            {{ index + 1 }}
                        </span>
                        <span
                            v-else
                            :class="[
                                'h-2.5 w-2.5 rounded-full',
                                getStepStatus(index) === 'current' ? 'bg-indigo-500' : 'bg-gray-300'
                            ]"
                        />
                    </span>

                    <!-- Step label -->
                    <span class="ml-3 text-left">
                        <span
                            :class="[
                                'block text-sm font-medium',
                                getStepStatus(index) === 'completed' ? 'text-indigo-600' : '',
                                getStepStatus(index) === 'current' ? 'text-indigo-600' : '',
                                getStepStatus(index) === 'upcoming' ? 'text-gray-500' : ''
                            ]"
                        >
                            {{ step.label || step }}
                        </span>
                        <span v-if="step.description" class="block text-xs text-gray-500">
                            {{ step.description }}
                        </span>
                    </span>
                </button>

                <!-- Connector line -->
                <div
                    v-if="index < steps.length - 1"
                    :class="[
                        'flex-1 h-0.5 mx-4',
                        getStepStatus(index) === 'completed' ? 'bg-indigo-500' : 'bg-gray-200'
                    ]"
                />
            </li>
        </ol>
    </nav>

    <!-- Vertical variant -->
    <nav v-else class="w-full">
        <ol class="space-y-4">
            <li
                v-for="(step, index) in steps"
                :key="step.id || index"
                class="relative"
            >
                <!-- Connector line -->
                <div
                    v-if="index < steps.length - 1"
                    :class="[
                        'absolute left-5 top-10 -ml-px h-full w-0.5',
                        getStepStatus(index) === 'completed' ? 'bg-indigo-500' : 'bg-gray-200'
                    ]"
                />

                <button
                    type="button"
                    :disabled="!clickable || index > currentIndex"
                    class="group relative flex items-start"
                    :class="clickable && index <= currentIndex ? 'cursor-pointer' : 'cursor-default'"
                    @click="handleStepClick(step, index)"
                >
                    <!-- Step indicator -->
                    <span
                        :class="[
                            'relative z-10 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 transition-colors',
                            getStepStatus(index) === 'completed' ? 'bg-indigo-500 border-indigo-500' : '',
                            getStepStatus(index) === 'current' ? 'border-indigo-500 bg-white' : '',
                            getStepStatus(index) === 'upcoming' ? 'border-gray-300 bg-white' : ''
                        ]"
                    >
                        <CheckIcon
                            v-if="getStepStatus(index) === 'completed'"
                            class="h-5 w-5 text-white"
                        />
                        <span
                            v-else-if="showNumbers"
                            :class="[
                                'text-sm font-medium',
                                getStepStatus(index) === 'current' ? 'text-indigo-500' : 'text-gray-500'
                            ]"
                        >
                            {{ index + 1 }}
                        </span>
                    </span>

                    <!-- Step content -->
                    <span class="ml-4 min-w-0 pt-1.5">
                        <span
                            :class="[
                                'block text-sm font-medium',
                                getStepStatus(index) === 'completed' ? 'text-indigo-600' : '',
                                getStepStatus(index) === 'current' ? 'text-indigo-600' : '',
                                getStepStatus(index) === 'upcoming' ? 'text-gray-500' : ''
                            ]"
                        >
                            {{ step.label || step }}
                        </span>
                        <span v-if="step.description" class="block text-sm text-gray-500">
                            {{ step.description }}
                        </span>
                    </span>
                </button>
            </li>
        </ol>
    </nav>
</template>
