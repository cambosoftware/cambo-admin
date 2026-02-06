<script setup>
import { computed } from 'vue'
import { useTheme } from '@/composables/useTheme'
import { SunIcon, MoonIcon, ComputerDesktopIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Toggle mode: 'simple' (dark/light only) or 'full' (system/dark/light)
     */
    mode: {
        type: String,
        default: 'simple',
        validator: (v) => ['simple', 'full'].includes(v)
    },
    /**
     * Size of the toggle button
     */
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    /**
     * Show labels next to icons
     */
    showLabel: {
        type: Boolean,
        default: false
    }
})

const { theme, isDark, toggleDarkLight, setTheme, THEME_SYSTEM, THEME_LIGHT, THEME_DARK } = useTheme()

const sizeClasses = computed(() => ({
    sm: 'p-1.5',
    md: 'p-2',
    lg: 'p-2.5'
}[props.size]))

const iconSizeClasses = computed(() => ({
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6'
}[props.size]))

const currentIcon = computed(() => {
    if (props.mode === 'full') {
        if (theme.value === THEME_SYSTEM) return ComputerDesktopIcon
        if (theme.value === THEME_DARK) return MoonIcon
        return SunIcon
    }
    return isDark.value ? MoonIcon : SunIcon
})

const currentLabel = computed(() => {
    if (props.mode === 'full') {
        if (theme.value === THEME_SYSTEM) return 'Système'
        if (theme.value === THEME_DARK) return 'Sombre'
        return 'Clair'
    }
    return isDark.value ? 'Sombre' : 'Clair'
})

const handleClick = () => {
    if (props.mode === 'simple') {
        toggleDarkLight()
    } else {
        // Cycle through: system -> dark -> light -> system
        if (theme.value === THEME_SYSTEM) {
            setTheme(THEME_DARK)
        } else if (theme.value === THEME_DARK) {
            setTheme(THEME_LIGHT)
        } else {
            setTheme(THEME_SYSTEM)
        }
    }
}
</script>

<template>
    <button
        type="button"
        :class="[
            sizeClasses,
            'inline-flex items-center gap-2 rounded-lg',
            'text-gray-500 hover:text-gray-700 hover:bg-gray-100',
            'dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700',
            'transition-colors duration-200',
            'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
            'dark:focus:ring-offset-gray-900'
        ]"
        :aria-label="currentLabel"
        @click="handleClick"
    >
        <component :is="currentIcon" :class="iconSizeClasses" />
        <span v-if="showLabel" class="text-sm font-medium">{{ currentLabel }}</span>
    </button>
</template>
