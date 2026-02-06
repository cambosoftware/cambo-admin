<script setup>
import { provide, ref, computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: null
    },
    variant: {
        type: String,
        default: 'underline',
        validator: (v) => ['underline', 'pills', 'bordered'].includes(v)
    }
})

const emit = defineEmits(['update:modelValue'])

const tabs = ref([])
const internalActive = ref(null)

const activeTab = computed({
    get: () => props.modelValue ?? internalActive.value ?? tabs.value[0]?.id,
    set: (val) => {
        internalActive.value = val
        emit('update:modelValue', val)
    }
})

const registerTab = (tab) => {
    tabs.value.push(tab)
    if (internalActive.value === null && tabs.value.length === 1) {
        internalActive.value = tab.id
    }
}

const unregisterTab = (id) => {
    tabs.value = tabs.value.filter(t => t.id !== id)
}

provide('tabs', { activeTab, registerTab, unregisterTab })

const tabClasses = computed(() => {
    const base = 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-colors cursor-pointer'
    const variants = {
        underline: {
            wrapper: 'border-b border-gray-200',
            inactive: `${base} border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 -mb-px`,
            active: `${base} border-b-2 border-primary-500 text-primary-600 -mb-px`
        },
        pills: {
            wrapper: 'flex gap-1',
            inactive: `${base} rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100`,
            active: `${base} rounded-lg bg-primary-100 text-primary-700`
        },
        bordered: {
            wrapper: 'border-b border-gray-200',
            inactive: `${base} border border-transparent rounded-t-lg text-gray-500 hover:text-gray-700 -mb-px`,
            active: `${base} border border-gray-200 border-b-white rounded-t-lg text-primary-600 bg-white -mb-px`
        }
    }
    return variants[props.variant]
})
</script>

<template>
    <div>
        <!-- Tab headers -->
        <div :class="['flex', tabClasses.wrapper]" role="tablist">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                type="button"
                role="tab"
                :class="activeTab === tab.id ? tabClasses.active : tabClasses.inactive"
                :aria-selected="activeTab === tab.id"
                @click="activeTab = tab.id"
            >
                <component v-if="tab.icon" :is="tab.icon" class="h-4 w-4" />
                {{ tab.label }}
                <span
                    v-if="tab.badge"
                    class="ml-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium rounded-full bg-gray-200 text-gray-600"
                >
                    {{ tab.badge }}
                </span>
            </button>
        </div>

        <!-- Tab content -->
        <div class="mt-4">
            <slot />
        </div>
    </div>
</template>
