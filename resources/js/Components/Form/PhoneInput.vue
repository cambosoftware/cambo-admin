<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    defaultCountry: {
        type: String,
        default: 'FR'
    },
    placeholder: {
        type: String,
        default: null
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    error: {
        type: [String, Boolean],
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'countryChange', 'focus', 'blur'])

const containerRef = ref(null)
const inputRef = ref(null)
const showDropdown = ref(false)
const search = ref('')
const selectedCountry = ref(props.defaultCountry)

const hasError = computed(() => !!props.error)

// Common countries with dial codes and flags
const countries = [
    { code: 'FR', name: 'France', dial: '+33', flag: '🇫🇷', placeholder: '06 12 34 56 78' },
    { code: 'BE', name: 'Belgique', dial: '+32', flag: '🇧🇪', placeholder: '0470 12 34 56' },
    { code: 'CH', name: 'Suisse', dial: '+41', flag: '🇨🇭', placeholder: '078 123 45 67' },
    { code: 'CA', name: 'Canada', dial: '+1', flag: '🇨🇦', placeholder: '(514) 123-4567' },
    { code: 'US', name: 'États-Unis', dial: '+1', flag: '🇺🇸', placeholder: '(201) 555-0123' },
    { code: 'GB', name: 'Royaume-Uni', dial: '+44', flag: '🇬🇧', placeholder: '07911 123456' },
    { code: 'DE', name: 'Allemagne', dial: '+49', flag: '🇩🇪', placeholder: '0171 1234567' },
    { code: 'ES', name: 'Espagne', dial: '+34', flag: '🇪🇸', placeholder: '612 34 56 78' },
    { code: 'IT', name: 'Italie', dial: '+39', flag: '🇮🇹', placeholder: '312 345 6789' },
    { code: 'PT', name: 'Portugal', dial: '+351', flag: '🇵🇹', placeholder: '912 345 678' },
    { code: 'NL', name: 'Pays-Bas', dial: '+31', flag: '🇳🇱', placeholder: '06 12345678' },
    { code: 'LU', name: 'Luxembourg', dial: '+352', flag: '🇱🇺', placeholder: '628 123 456' },
    { code: 'KH', name: 'Cambodge', dial: '+855', flag: '🇰🇭', placeholder: '012 345 678' },
    { code: 'TH', name: 'Thaïlande', dial: '+66', flag: '🇹🇭', placeholder: '081 234 5678' },
    { code: 'VN', name: 'Vietnam', dial: '+84', flag: '🇻🇳', placeholder: '091 234 56 78' },
    { code: 'JP', name: 'Japon', dial: '+81', flag: '🇯🇵', placeholder: '090-1234-5678' },
    { code: 'CN', name: 'Chine', dial: '+86', flag: '🇨🇳', placeholder: '131 2345 6789' },
    { code: 'IN', name: 'Inde', dial: '+91', flag: '🇮🇳', placeholder: '091234 56789' },
    { code: 'MA', name: 'Maroc', dial: '+212', flag: '🇲🇦', placeholder: '0650-123456' },
    { code: 'TN', name: 'Tunisie', dial: '+216', flag: '🇹🇳', placeholder: '20 123 456' },
    { code: 'DZ', name: 'Algérie', dial: '+213', flag: '🇩🇿', placeholder: '0551 23 45 67' },
    { code: 'SN', name: 'Sénégal', dial: '+221', flag: '🇸🇳', placeholder: '70 123 45 67' },
    { code: 'CI', name: 'Côte d\'Ivoire', dial: '+225', flag: '🇨🇮', placeholder: '01 23 45 67 89' },
    { code: 'CM', name: 'Cameroun', dial: '+237', flag: '🇨🇲', placeholder: '6 71 23 45 67' },
    { code: 'AU', name: 'Australie', dial: '+61', flag: '🇦🇺', placeholder: '0412 345 678' },
    { code: 'BR', name: 'Brésil', dial: '+55', flag: '🇧🇷', placeholder: '(11) 96123-4567' },
    { code: 'MX', name: 'Mexique', dial: '+52', flag: '🇲🇽', placeholder: '222 123 4567' }
]

const currentCountry = computed(() => countries.find(c => c.code === selectedCountry.value) || countries[0])

const filteredCountries = computed(() => {
    if (!search.value) return countries
    const q = search.value.toLowerCase()
    return countries.filter(c =>
        c.name.toLowerCase().includes(q) ||
        c.dial.includes(q) ||
        c.code.toLowerCase().includes(q)
    )
})

const currentPlaceholder = computed(() => props.placeholder || currentCountry.value?.placeholder || '')

const sizeClasses = computed(() => {
    const sizes = {
        sm: 'py-1.5 text-xs',
        md: 'py-2 text-sm',
        lg: 'py-2.5 text-base'
    }
    return sizes[props.size]
})

function selectCountry(country) {
    selectedCountry.value = country.code
    showDropdown.value = false
    search.value = ''
    emit('countryChange', country)
    // Update the full phone value
    updateFullValue(getLocalNumber())
    inputRef.value?.focus()
}

function getLocalNumber() {
    // Remove country code prefix from the modelValue
    const dial = currentCountry.value.dial
    if (props.modelValue.startsWith(dial)) {
        return props.modelValue.substring(dial.length).trim()
    }
    return props.modelValue.replace(/^\+\d+\s*/, '')
}

function updateFullValue(localNum) {
    const full = localNum ? `${currentCountry.value.dial} ${localNum}` : ''
    emit('update:modelValue', full)
    emit('change', full)
}

function onInput(e) {
    // Allow only digits, spaces, dashes, parens
    const cleaned = e.target.value.replace(/[^\d\s\-()]/g, '')
    updateFullValue(cleaned)
}

function onClickOutside(e) {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        showDropdown.value = false
        search.value = ''
    }
}

onMounted(() => document.addEventListener('mousedown', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('mousedown', onClickOutside))
</script>

<template>
    <div ref="containerRef" class="relative flex">
        <!-- Country selector -->
        <button
            type="button"
            :class="[
                'inline-flex items-center gap-1.5 rounded-l-lg border border-r-0 bg-gray-50 transition-colors cursor-pointer',
                hasError ? 'border-red-300' : 'border-gray-300',
                disabled ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-100',
                size === 'sm' ? 'px-2 text-xs' : size === 'lg' ? 'px-3 text-base' : 'px-2.5 text-sm',
                sizeClasses
            ]"
            :disabled="disabled"
            @click="showDropdown = !showDropdown"
        >
            <span class="text-base leading-none">{{ currentCountry.flag }}</span>
            <span class="text-gray-600 font-medium">{{ currentCountry.dial }}</span>
            <svg class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Phone input -->
        <input
            ref="inputRef"
            type="tel"
            :value="getLocalNumber()"
            :placeholder="currentPlaceholder"
            :disabled="disabled"
            :class="[
                'block w-full rounded-r-lg border bg-white transition-colors duration-150',
                'placeholder:text-gray-400',
                'focus:outline-none focus:ring-2 focus:ring-offset-0',
                'disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed',
                hasError
                    ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
                    : 'border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500/20',
                sizeClasses,
                size === 'sm' ? 'px-2.5' : size === 'lg' ? 'px-4' : 'px-3'
            ]"
            @input="onInput"
            @focus="$emit('focus', $event)"
            @blur="$emit('blur', $event)"
        />

        <!-- Country dropdown -->
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="showDropdown"
                class="absolute left-0 z-50 mt-1 w-72 rounded-lg border border-gray-200 bg-white shadow-lg"
                :style="{ top: '100%' }"
            >
                <!-- Search -->
                <div class="border-b border-gray-100 p-2">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Rechercher un pays..."
                        class="w-full rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500/20"
                    />
                </div>

                <!-- Countries list -->
                <ul class="max-h-48 overflow-auto py-1">
                    <li
                        v-for="country in filteredCountries"
                        :key="country.code"
                        :class="[
                            'flex items-center gap-2 px-3 py-1.5 text-sm cursor-pointer transition-colors',
                            selectedCountry === country.code ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50'
                        ]"
                        @click="selectCountry(country)"
                    >
                        <span class="text-base leading-none">{{ country.flag }}</span>
                        <span class="flex-1">{{ country.name }}</span>
                        <span class="text-xs text-gray-500">{{ country.dial }}</span>
                    </li>
                </ul>
            </div>
        </Transition>
    </div>
</template>
