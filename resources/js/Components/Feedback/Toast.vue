<script setup>
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'info',
        validator: (v) => ['info', 'success', 'warning', 'danger'].includes(v)
    },
    title: {
        type: String,
        default: null
    },
    message: {
        type: String,
        default: ''
    },
    duration: {
        type: Number,
        default: 5000
    },
    dismissible: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['close'])

const visible = ref(true)
const progress = ref(100)
let timer = null
let startTime = null

const variantConfig = computed(() => {
    const configs = {
        info: { bg: 'bg-sky-500', icon: 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z' },
        success: { bg: 'bg-emerald-500', icon: 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z' },
        warning: { bg: 'bg-amber-500', icon: 'M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z' },
        danger: { bg: 'bg-red-500', icon: 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z' }
    }
    return configs[props.variant]
})

const close = () => {
    visible.value = false
    clearInterval(timer)
    emit('close')
}

onMounted(() => {
    if (props.duration > 0) {
        startTime = Date.now()
        timer = setInterval(() => {
            const elapsed = Date.now() - startTime
            progress.value = Math.max(0, 100 - (elapsed / props.duration) * 100)
            if (progress.value <= 0) close()
        }, 50)
    }
})
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out transform"
        enter-from-class="opacity-0 translate-x-8"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition duration-200 ease-in transform"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-8"
    >
        <div
            v-if="visible"
            class="w-80 bg-white rounded-lg shadow-xl ring-1 ring-black/5 overflow-hidden"
        >
            <div class="flex items-start gap-3 p-4">
                <!-- Icon -->
                <div :class="['flex-shrink-0 p-1 rounded-full text-white', variantConfig.bg]">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" :d="variantConfig.icon" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0 pt-0.5">
                    <p v-if="title" class="text-sm font-semibold text-gray-900">{{ title }}</p>
                    <p class="text-sm text-gray-600" :class="title ? 'mt-0.5' : ''">
                        <slot>{{ message }}</slot>
                    </p>
                </div>

                <!-- Close -->
                <button
                    v-if="dismissible"
                    type="button"
                    class="flex-shrink-0 p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                    @click="close"
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </div>

            <!-- Progress bar -->
            <div v-if="duration > 0" class="h-0.5 bg-gray-100">
                <div
                    :class="['h-full transition-none', variantConfig.bg]"
                    :style="{ width: progress + '%' }"
                />
            </div>
        </div>
    </Transition>
</template>
