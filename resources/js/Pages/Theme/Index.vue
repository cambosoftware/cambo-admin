<script setup>
import { ref, computed, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Select from '@/Components/Form/Select.vue'
import ColorPicker from '@/Components/Form/ColorPicker.vue'
import Modal from '@/Components/Overlays/Modal.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import {
    PaintBrushIcon,
    CheckIcon,
    TrashIcon,
    PlusIcon,
    EyeIcon,
    SwatchIcon,
    SparklesIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    themes: Object,
    currentTheme: Object,
    currentThemeKey: String,
    variableGroups: Object,
})

// State
const activeTab = ref('themes')
const customizeModal = ref(false)
const deleteModal = ref(false)
const themeToDelete = ref(null)
const previewStyle = ref('')

// Customization form
const customForm = useForm({
    name: '',
    base: 'default',
    variables: {},
})

// Initialize customization from base theme
const initCustomization = (baseThemeKey) => {
    const baseTheme = props.themes[baseThemeKey]
    if (!baseTheme) return

    customForm.base = baseThemeKey
    customForm.name = `${baseTheme.name} Custom`
    customForm.variables = { ...baseTheme.variables }
}

// Open customize modal
const openCustomize = (themeKey = 'default') => {
    initCustomization(themeKey)
    customizeModal.value = true
}

// Apply theme
const applyTheme = (themeKey) => {
    router.post('/theme/switch', { theme: themeKey }, {
        preserveScroll: true,
    })
}

// Save custom theme
const saveCustomTheme = () => {
    customForm.post('/theme/customize', {
        onSuccess: () => {
            customizeModal.value = false
        }
    })
}

// Confirm delete
const confirmDelete = (themeKey) => {
    themeToDelete.value = themeKey
    deleteModal.value = true
}

// Delete theme
const doDelete = () => {
    router.delete('/theme', {
        data: { theme: themeToDelete.value },
        onSuccess: () => {
            deleteModal.value = false
            themeToDelete.value = null
        }
    })
}

// Update preview style
const updatePreview = () => {
    let css = ':root {'
    for (const [name, value] of Object.entries(customForm.variables)) {
        css += `--${name}: ${value};`
    }
    css += '}'
    previewStyle.value = css
}

// Watch for variable changes
watch(() => customForm.variables, updatePreview, { deep: true })

// Get variable type and options
const getVariableConfig = (key) => {
    for (const group of Object.values(props.variableGroups)) {
        if (group.variables[key]) {
            return group.variables[key]
        }
    }
    return { label: key, type: 'text' }
}
</script>

<template>
    <AdminLayout title="Apparence">
        <PageHeader
            title="Thèmes & Apparence"
            subtitle="Personnalisez l'apparence de votre interface"
        >
            <template #actions>
                <Button variant="primary" :icon-left="PlusIcon" @click="openCustomize('default')">
                    Créer un thème
                </Button>
            </template>
        </PageHeader>

        <!-- Flash message -->
        <Alert v-if="$page.props.flash?.success" variant="success" dismissible class="mb-6">
            {{ $page.props.flash.success }}
        </Alert>

        <!-- Theme selection -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Thèmes disponibles
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card
                    v-for="(theme, key) in themes"
                    :key="key"
                    :class="[
                        'relative overflow-hidden transition-all cursor-pointer',
                        currentThemeKey === key
                            ? 'ring-2 ring-indigo-500 shadow-lg'
                            : 'hover:shadow-md'
                    ]"
                    @click="applyTheme(key)"
                >
                    <!-- Theme preview colors -->
                    <div class="h-24 flex">
                        <div
                            class="flex-1"
                            :style="{ backgroundColor: theme.previewColors?.primary }"
                        />
                        <div
                            class="flex-1"
                            :style="{ backgroundColor: theme.previewColors?.secondary }"
                        />
                        <div
                            class="flex-1"
                            :style="{ backgroundColor: theme.previewColors?.accent }"
                        />
                    </div>

                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ theme.name }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <Badge v-if="theme.custom" variant="info" size="sm">
                                    Custom
                                </Badge>
                                <CheckIcon
                                    v-if="currentThemeKey === key"
                                    class="h-5 w-5 text-indigo-500"
                                />
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            {{ theme.description }}
                        </p>

                        <div class="flex items-center gap-2">
                            <Button
                                variant="secondary"
                                size="sm"
                                :icon-left="PaintBrushIcon"
                                @click.stop="openCustomize(key)"
                            >
                                Personnaliser
                            </Button>
                            <Button
                                v-if="theme.custom"
                                variant="ghost"
                                size="sm"
                                :icon-left="TrashIcon"
                                class="text-red-600"
                                @click.stop="confirmDelete(key)"
                            />
                        </div>
                    </div>

                    <!-- Active indicator -->
                    <div
                        v-if="currentThemeKey === key"
                        class="absolute top-2 right-2 bg-indigo-500 text-white px-2 py-1 rounded text-xs font-medium"
                    >
                        Actif
                    </div>
                </Card>
            </div>
        </div>

        <!-- Quick customization -->
        <Card>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-6">
                    <SwatchIcon class="h-6 w-6 text-indigo-500" />
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Personnalisation rapide
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Color pickers for current theme -->
                    <FormGroup label="Couleur principale">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-700"
                                :style="{ backgroundColor: currentTheme.variables['indigo-500'] }"
                            />
                            <span class="text-sm text-gray-500">
                                {{ currentTheme.variables['indigo-500'] }}
                            </span>
                        </div>
                    </FormGroup>

                    <FormGroup label="Couleur secondaire">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-700"
                                :style="{ backgroundColor: currentTheme.variables['secondary-500'] }"
                            />
                            <span class="text-sm text-gray-500">
                                {{ currentTheme.variables['secondary-500'] }}
                            </span>
                        </div>
                    </FormGroup>

                    <FormGroup label="Couleur d'accent">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-700"
                                :style="{ backgroundColor: currentTheme.variables['accent-500'] }"
                            />
                            <span class="text-sm text-gray-500">
                                {{ currentTheme.variables['accent-500'] }}
                            </span>
                        </div>
                    </FormGroup>

                    <FormGroup label="Arrondis">
                        <span class="text-sm text-gray-500">
                            {{ currentTheme.variables['border-radius'] }}
                        </span>
                    </FormGroup>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <Button
                        variant="primary"
                        :icon-left="SparklesIcon"
                        @click="openCustomize(currentThemeKey)"
                    >
                        Personnaliser ce thème
                    </Button>
                </div>
            </div>
        </Card>

        <!-- Customize Modal -->
        <Modal
            :show="customizeModal"
            title="Personnaliser le thème"
            size="lg"
            @close="customizeModal = false"
        >
            <form @submit.prevent="saveCustomTheme" class="space-y-6">
                <!-- Theme name -->
                <FormGroup label="Nom du thème" :error="customForm.errors.name">
                    <Input v-model="customForm.name" placeholder="Mon thème personnalisé" />
                </FormGroup>

                <!-- Variable groups -->
                <div
                    v-for="(group, groupKey) in variableGroups"
                    :key="groupKey"
                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
                >
                    <h3 class="font-medium text-gray-900 dark:text-white mb-4">
                        {{ group.label }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="(config, varKey) in group.variables"
                            :key="varKey"
                        >
                            <FormGroup :label="config.label">
                                <!-- Color picker -->
                                <ColorPicker
                                    v-if="config.type === 'color'"
                                    v-model="customForm.variables[varKey]"
                                />

                                <!-- Select -->
                                <Select
                                    v-else-if="config.type === 'select'"
                                    v-model="customForm.variables[varKey]"
                                    :options="Object.entries(config.options).map(([value, label]) => ({ value, label }))"
                                />

                                <!-- Text input -->
                                <Input
                                    v-else
                                    v-model="customForm.variables[varKey]"
                                />
                            </FormGroup>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 dark:text-white mb-4">
                        Aperçu
                    </h3>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 rounded-lg"
                            :style="{ backgroundColor: customForm.variables['indigo-500'] }"
                        />
                        <div
                            class="w-16 h-16 rounded-lg"
                            :style="{ backgroundColor: customForm.variables['secondary-500'] }"
                        />
                        <div
                            class="w-16 h-16 rounded-lg"
                            :style="{ backgroundColor: customForm.variables['accent-500'] }"
                        />
                        <div class="flex-1">
                            <Button
                                variant="primary"
                                :style="{ backgroundColor: customForm.variables['indigo-500'] }"
                            >
                                Bouton exemple
                            </Button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <Button variant="secondary" @click="customizeModal = false">
                        Annuler
                    </Button>
                    <Button type="submit" variant="primary" :loading="customForm.processing">
                        Enregistrer
                    </Button>
                </div>
            </form>
        </Modal>

        <!-- Delete confirmation -->
        <ConfirmModal
            :show="deleteModal"
            title="Supprimer le thème"
            message="Cette action est irréversible. Le thème personnalisé sera supprimé définitivement."
            confirm-text="Supprimer"
            variant="danger"
            @close="deleteModal = false"
            @confirm="doDelete"
        />
    </AdminLayout>
</template>
