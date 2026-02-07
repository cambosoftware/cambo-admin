<script setup>
import { computed } from 'vue'
import { useTheme } from '@/Composables/useTheme'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import { SunIcon, MoonIcon, ComputerDesktopIcon, ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Size of the toggle button
     */
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    /**
     * Show chevron icon
     */
    showChevron: {
        type: Boolean,
        default: true
    }
})

const { theme, isDark, setTheme, THEME_SYSTEM, THEME_LIGHT, THEME_DARK } = useTheme()

const options = [
    { value: THEME_SYSTEM, label: 'Système', icon: ComputerDesktopIcon },
    { value: THEME_LIGHT, label: 'Clair', icon: SunIcon },
    { value: THEME_DARK, label: 'Sombre', icon: MoonIcon }
]

const currentOption = computed(() => options.find(o => o.value === theme.value))

const sizeClasses = computed(() => ({
    sm: 'p-1.5 gap-1.5',
    md: 'p-2 gap-2',
    lg: 'p-2.5 gap-2.5'
}[props.size]))

const iconSizeClasses = computed(() => ({
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6'
}[props.size]))
</script>

<template>
    <Dropdown position="bottom-end">
        <template #trigger>
            <button
                type="button"
                :class="[
                    sizeClasses,
                    'inline-flex items-center rounded-lg',
                    'text-gray-500 hover:text-gray-700 hover:bg-gray-100',
                    'dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700',
                    'transition-colors duration-200',
                    'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
                    'dark:focus:ring-offset-gray-900'
                ]"
                :aria-label="currentOption?.label"
            >
                <component :is="currentOption?.icon" :class="iconSizeClasses" />
                <ChevronDownIcon v-if="showChevron" class="h-4 w-4" />
            </button>
        </template>

        <template v-for="option in options" :key="option.value">
            <DropdownItem
                :icon="option.icon"
                @click="setTheme(option.value)"
            >
                <div class="flex items-center justify-between w-full">
                    <span>{{ option.label }}</span>
                    <CheckIcon
                        v-if="theme === option.value"
                        class="h-4 w-4 text-primary-600 dark:text-primary-400"
                    />
                </div>
            </DropdownItem>
        </template>
    </Dropdown>
</template>
