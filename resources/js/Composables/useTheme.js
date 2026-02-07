import { ref, watch, onMounted } from 'vue'

export const THEME_SYSTEM = 'system'
export const THEME_LIGHT = 'light'
export const THEME_DARK = 'dark'

const theme = ref(THEME_SYSTEM)
const isDark = ref(false)

const getSystemTheme = () => {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? THEME_DARK : THEME_LIGHT
}

const applyTheme = (themeValue) => {
    const effectiveTheme = themeValue === THEME_SYSTEM ? getSystemTheme() : themeValue
    isDark.value = effectiveTheme === THEME_DARK

    if (effectiveTheme === THEME_DARK) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
}

const setTheme = (newTheme) => {
    theme.value = newTheme
    localStorage.setItem('theme', newTheme)
    applyTheme(newTheme)
}

const initTheme = () => {
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme && [THEME_SYSTEM, THEME_LIGHT, THEME_DARK].includes(savedTheme)) {
        theme.value = savedTheme
    }
    applyTheme(theme.value)

    // Watch for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (theme.value === THEME_SYSTEM) {
            applyTheme(THEME_SYSTEM)
        }
    })
}

export function useTheme() {
    onMounted(() => {
        initTheme()
    })

    return {
        theme,
        isDark,
        setTheme,
        THEME_SYSTEM,
        THEME_LIGHT,
        THEME_DARK,
    }
}
