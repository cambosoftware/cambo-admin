import { ref, computed, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

// Global state
const translations = ref({})
const currentLocale = ref('en')
const isRtl = ref(false)
const supportedLocales = ref([])

/**
 * Translation composable
 */
export function useTranslation() {
    const page = usePage()

    // Initialize from page props
    const init = () => {
        if (page.props.translations) {
            translations.value = page.props.translations
        }
        if (page.props.locale) {
            currentLocale.value = page.props.locale
        }
        if (page.props.locales) {
            supportedLocales.value = page.props.locales
        }
        if (page.props.isRtl !== undefined) {
            isRtl.value = page.props.isRtl
        }
    }

    // Translate function
    const t = (key, replacements = {}) => {
        let translation = getNestedValue(translations.value, key)

        if (translation === undefined) {
            return key
        }

        // Handle replacements (e.g., :name, :count)
        Object.keys(replacements).forEach(k => {
            translation = translation.replace(new RegExp(`:${k}`, 'g'), replacements[k])
        })

        return translation
    }

    // Choice function for pluralization
    const tc = (key, count, replacements = {}) => {
        let translation = getNestedValue(translations.value, key)

        if (translation === undefined) {
            return key
        }

        // Handle Laravel-style pluralization (singular|plural)
        if (typeof translation === 'string' && translation.includes('|')) {
            const [singular, plural] = translation.split('|')
            translation = count === 1 ? singular : plural
        }

        // Replace :count
        translation = translation.replace(/:count/g, count)

        // Handle other replacements
        Object.keys(replacements).forEach(k => {
            translation = translation.replace(new RegExp(`:${k}`, 'g'), replacements[k])
        })

        return translation
    }

    // Check if translation exists
    const te = (key) => {
        return getNestedValue(translations.value, key) !== undefined
    }

    // Switch locale
    const setLocale = (locale) => {
        router.post('/locale/switch', { locale }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                currentLocale.value = locale
                // Reload page to get new translations
                window.location.reload()
            }
        })
    }

    // Get nested value from object using dot notation
    const getNestedValue = (obj, key) => {
        return key.split('.').reduce((o, k) => (o || {})[k], obj)
    }

    // Date formatting with locale
    const formatDate = (date, options = {}) => {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        }
        return new Intl.DateTimeFormat(currentLocale.value, { ...defaultOptions, ...options }).format(new Date(date))
    }

    // Number formatting with locale
    const formatNumber = (number, options = {}) => {
        return new Intl.NumberFormat(currentLocale.value, options).format(number)
    }

    // Currency formatting with locale
    const formatCurrency = (amount, currency = 'EUR') => {
        return new Intl.NumberFormat(currentLocale.value, {
            style: 'currency',
            currency,
        }).format(amount)
    }

    return {
        t,
        tc,
        te,
        currentLocale: computed(() => currentLocale.value),
        isRtl: computed(() => isRtl.value),
        supportedLocales: computed(() => supportedLocales.value),
        setLocale,
        formatDate,
        formatNumber,
        formatCurrency,
        init,
    }
}

/**
 * Vue plugin for translations
 */
export const TranslationPlugin = {
    install(app, options = {}) {
        const { t, tc, te, formatDate, formatNumber, formatCurrency } = useTranslation()

        // Global methods
        app.config.globalProperties.$t = t
        app.config.globalProperties.$tc = tc
        app.config.globalProperties.$te = te
        app.config.globalProperties.$formatDate = formatDate
        app.config.globalProperties.$formatNumber = formatNumber
        app.config.globalProperties.$formatCurrency = formatCurrency

        // Provide for composition API
        app.provide('t', t)
        app.provide('tc', tc)
        app.provide('te', te)
    }
}

export default useTranslation
