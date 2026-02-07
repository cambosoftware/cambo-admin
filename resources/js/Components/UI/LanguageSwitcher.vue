<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import { GlobeAltIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Display mode: dropdown, list, or flags
     */
    mode: {
        type: String,
        default: 'dropdown',
        validator: (v) => ['dropdown', 'list', 'flags'].includes(v)
    },
    /**
     * Show language name
     */
    showName: {
        type: Boolean,
        default: true
    },
    /**
     * Show flag emoji
     */
    showFlag: {
        type: Boolean,
        default: true
    },
    /**
     * Size: sm, md, lg
     */
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    }
})

const page = usePage()

// Supported locales with their info
const locales = {
    en: { name: 'English', native: 'English', flag: '🇬🇧', rtl: false },
    fr: { name: 'French', native: 'Français', flag: '🇫🇷', rtl: false },
    es: { name: 'Spanish', native: 'Español', flag: '🇪🇸', rtl: false },
    de: { name: 'German', native: 'Deutsch', flag: '🇩🇪', rtl: false },
    ar: { name: 'Arabic', native: 'العربية', flag: '🇸🇦', rtl: true },
}

// Get supported locales from config or default
const supportedLocales = computed(() => {
    return page.props.supportedLocales || ['en', 'fr']
})

// Current locale
const currentLocale = computed(() => {
    return page.props.locale || 'en'
})

// Current locale info
const currentLocaleInfo = computed(() => {
    return locales[currentLocale.value] || locales['en']
})

// Available locales
const availableLocales = computed(() => {
    return supportedLocales.value
        .filter(code => locales[code])
        .map(code => ({
            code,
            ...locales[code]
        }))
})

// Switch locale
const switchLocale = (locale) => {
    if (locale === currentLocale.value) return

    router.post('/locale/switch', { locale }, {
        preserveState: false,
        preserveScroll: true,
    })
}

// Size classes
const sizeClasses = computed(() => {
    return {
        sm: 'text-xs px-2 py-1',
        md: 'text-sm px-3 py-2',
        lg: 'text-base px-4 py-2.5',
    }[props.size]
})
</script>

<template>
    <!-- Dropdown mode -->
    <Dropdown v-if="mode === 'dropdown'" align="right">
        <template #trigger>
            <button
                :class="[
                    'flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-700',
                    'hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors',
                    sizeClasses
                ]"
            >
                <GlobeAltIcon v-if="!showFlag" class="h-5 w-5 text-gray-500" />
                <span v-if="showFlag" class="text-lg">{{ currentLocaleInfo.flag }}</span>
                <span v-if="showName" class="text-gray-700 dark:text-gray-300">
                    {{ currentLocaleInfo.native }}
                </span>
            </button>
        </template>

        <DropdownItem
            v-for="locale in availableLocales"
            :key="locale.code"
            @click="switchLocale(locale.code)"
        >
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <span v-if="showFlag" class="text-lg">{{ locale.flag }}</span>
                    <span>{{ locale.native }}</span>
                </div>
                <CheckIcon
                    v-if="locale.code === currentLocale"
                    class="h-4 w-4 text-indigo-500"
                />
            </div>
        </DropdownItem>
    </Dropdown>

    <!-- List mode -->
    <div v-else-if="mode === 'list'" class="space-y-1">
        <button
            v-for="locale in availableLocales"
            :key="locale.code"
            :class="[
                'w-full flex items-center justify-between px-3 py-2 rounded-lg transition-colors',
                locale.code === currentLocale
                    ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400'
                    : 'hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
            @click="switchLocale(locale.code)"
        >
            <div class="flex items-center gap-2">
                <span v-if="showFlag" class="text-lg">{{ locale.flag }}</span>
                <span>{{ locale.native }}</span>
            </div>
            <CheckIcon
                v-if="locale.code === currentLocale"
                class="h-4 w-4"
            />
        </button>
    </div>

    <!-- Flags only mode -->
    <div v-else class="flex items-center gap-1">
        <button
            v-for="locale in availableLocales"
            :key="locale.code"
            :class="[
                'p-1.5 rounded-lg transition-colors text-xl',
                locale.code === currentLocale
                    ? 'bg-indigo-100 dark:bg-indigo-900/30 ring-2 ring-indigo-500'
                    : 'hover:bg-gray-100 dark:hover:bg-gray-700 opacity-60 hover:opacity-100'
            ]"
            :title="locale.native"
            @click="switchLocale(locale.code)"
        >
            {{ locale.flag }}
        </button>
    </div>
</template>
