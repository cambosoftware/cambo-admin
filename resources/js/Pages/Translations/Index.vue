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
import Textarea from '@/Components/Form/Textarea.vue'
import Select from '@/Components/Form/Select.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import Modal from '@/Components/Overlays/Modal.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import {
    LanguageIcon,
    PlusIcon,
    PencilIcon,
    TrashIcon,
    ArrowDownTrayIcon,
    ArrowUpTrayIcon,
    ExclamationTriangleIcon,
    CheckCircleIcon,
    GlobeAltIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    currentLocale: String,
    locales: Object,
    translations: [Object, Array],
    groups: Array,
    activeGroup: String,
    missing: Array,
})

// State
const search = ref('')
const editModal = ref(false)
const addModal = ref(false)
const deleteModal = ref(false)
const importModal = ref(false)
const selectedTranslation = ref(null)

// Form for editing
const editForm = useForm({
    locale: '',
    group: '',
    key: '',
    value: '',
})

// Form for adding
const addForm = useForm({
    locale: props.currentLocale,
    group: '',
    key: '',
    value: '',
})

// Locale options
const localeOptions = computed(() => {
    return Object.entries(props.locales).map(([code, info]) => ({
        value: code,
        label: `${info.flag} ${info.native}`,
    }))
})

// Group options
const groupOptions = computed(() => {
    return props.groups.map(g => ({ value: g, label: g }))
})

// Current locale info
const currentLocaleInfo = computed(() => {
    return props.locales[props.currentLocale] || {}
})

// Flattened translations for display
const flattenedTranslations = computed(() => {
    if (props.activeGroup) {
        return flattenObject(props.translations, props.activeGroup)
    }

    const all = []
    for (const [group, items] of Object.entries(props.translations)) {
        if (typeof items === 'object') {
            all.push(...flattenObject(items, group))
        }
    }
    return all
})

// Filtered translations
const filteredTranslations = computed(() => {
    if (!search.value) return flattenedTranslations.value

    const term = search.value.toLowerCase()
    return flattenedTranslations.value.filter(t =>
        t.key.toLowerCase().includes(term) ||
        t.value.toLowerCase().includes(term)
    )
})

// Flatten object with dot notation
function flattenObject(obj, prefix = '', result = []) {
    for (const [key, value] of Object.entries(obj)) {
        const fullKey = prefix ? `${prefix}.${key}` : key

        if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
            flattenObject(value, fullKey, result)
        } else {
            result.push({
                key: fullKey,
                value: String(value ?? ''),
                group: prefix.split('.')[0],
            })
        }
    }
    return result
}

// Methods
const selectLocale = (locale) => {
    router.get('/translations', { locale }, { preserveState: true })
}

const selectGroup = (group) => {
    router.get('/translations', {
        locale: props.currentLocale,
        group: group || undefined
    }, { preserveState: true })
}

const openEdit = (trans) => {
    selectedTranslation.value = trans
    const [group, ...keyParts] = trans.key.split('.')
    editForm.locale = props.currentLocale
    editForm.group = group
    editForm.key = keyParts.join('.')
    editForm.value = trans.value
    editModal.value = true
}

const saveEdit = () => {
    editForm.put('/translations', {
        onSuccess: () => {
            editModal.value = false
        }
    })
}

const openAdd = () => {
    addForm.reset()
    addForm.locale = props.currentLocale
    addForm.group = props.activeGroup || (props.groups[0] ?? 'messages')
    addModal.value = true
}

const saveAdd = () => {
    addForm.post('/translations', {
        onSuccess: () => {
            addModal.value = false
            addForm.reset()
        }
    })
}

const confirmDelete = (trans) => {
    selectedTranslation.value = trans
    deleteModal.value = true
}

const doDelete = () => {
    const [group, ...keyParts] = selectedTranslation.value.key.split('.')
    router.delete('/translations', {
        data: {
            locale: props.currentLocale,
            group,
            key: keyParts.join('.'),
        },
        onSuccess: () => {
            deleteModal.value = false
            selectedTranslation.value = null
        }
    })
}

const exportTranslations = () => {
    window.location.href = `/translations/export?locale=${props.currentLocale}`
}
</script>

<template>
    <AdminLayout title="Traductions">
        <PageHeader
            title="Traductions"
            subtitle="Gérer les traductions de l'application"
        >
            <template #actions>
                <Button variant="secondary" :icon-left="ArrowDownTrayIcon" @click="exportTranslations">
                    Exporter
                </Button>
                <Button variant="secondary" :icon-left="ArrowUpTrayIcon" @click="importModal = true">
                    Importer
                </Button>
                <Button variant="primary" :icon-left="PlusIcon" @click="openAdd">
                    Ajouter
                </Button>
            </template>
        </PageHeader>

        <!-- Missing translations alert -->
        <Alert
            v-if="missing.length > 0"
            variant="warning"
            class="mb-6"
        >
            <template #title>
                <div class="flex items-center gap-2">
                    <ExclamationTriangleIcon class="h-5 w-5" />
                    {{ missing.length }} traduction(s) manquante(s)
                </div>
            </template>
            <p class="text-sm">
                Comparé à l'anglais, certaines traductions sont manquantes pour cette langue.
            </p>
        </Alert>

        <div class="flex gap-6">
            <!-- Sidebar -->
            <div class="w-64 flex-shrink-0 space-y-4">
                <!-- Locale selector -->
                <Card>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                            Langue
                        </h3>
                        <div class="space-y-1">
                            <button
                                v-for="(info, code) in locales"
                                :key="code"
                                :class="[
                                    'w-full flex items-center gap-2 px-3 py-2 rounded-lg text-left transition-colors',
                                    currentLocale === code
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                                @click="selectLocale(code)"
                            >
                                <span class="text-lg">{{ info.flag }}</span>
                                <span class="flex-1 text-sm font-medium">{{ info.native }}</span>
                                <Badge v-if="info.rtl" variant="info" size="sm">RTL</Badge>
                            </button>
                        </div>
                    </div>
                </Card>

                <!-- Groups -->
                <Card>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                            Groupes
                        </h3>
                        <div class="space-y-1">
                            <button
                                :class="[
                                    'w-full flex items-center gap-2 px-3 py-2 rounded-lg text-left text-sm transition-colors',
                                    !activeGroup
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                                @click="selectGroup(null)"
                            >
                                <GlobeAltIcon class="h-4 w-4" />
                                <span>Tous</span>
                            </button>
                            <button
                                v-for="group in groups"
                                :key="group"
                                :class="[
                                    'w-full flex items-center gap-2 px-3 py-2 rounded-lg text-left text-sm transition-colors',
                                    activeGroup === group
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400'
                                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                                ]"
                                @click="selectGroup(group)"
                            >
                                <LanguageIcon class="h-4 w-4" />
                                <span>{{ group }}</span>
                            </button>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <Card>
                    <!-- Search -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <SearchInput
                            v-model="search"
                            placeholder="Rechercher une traduction..."
                            class="max-w-sm"
                        />
                    </div>

                    <!-- Translations list -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div
                            v-for="trans in filteredTranslations"
                            :key="trans.key"
                            class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-600 dark:text-gray-400">
                                            {{ trans.key }}
                                        </code>
                                        <Badge
                                            v-if="missing.some(m => m.key === trans.key)"
                                            variant="warning"
                                            size="sm"
                                        >
                                            Manquant
                                        </Badge>
                                    </div>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ trans.value || '(vide)' }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        :icon-left="PencilIcon"
                                        @click="openEdit(trans)"
                                    />
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        :icon-left="TrashIcon"
                                        class="text-red-600 hover:text-red-700"
                                        @click="confirmDelete(trans)"
                                    />
                                </div>
                            </div>
                        </div>

                        <EmptyState
                            v-if="filteredTranslations.length === 0"
                            title="Aucune traduction"
                            description="Aucune traduction trouvée pour cette recherche."
                            class="py-12"
                        />
                    </div>
                </Card>
            </div>
        </div>

        <!-- Edit Modal -->
        <Modal :show="editModal" title="Modifier la traduction" @close="editModal = false">
            <form @submit.prevent="saveEdit" class="space-y-4">
                <FormGroup label="Clé">
                    <code class="text-sm bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded block">
                        {{ editForm.group }}.{{ editForm.key }}
                    </code>
                </FormGroup>

                <FormGroup label="Valeur" :error="editForm.errors.value">
                    <Textarea
                        v-model="editForm.value"
                        rows="4"
                        placeholder="Entrez la traduction..."
                    />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary" @click="editModal = false">
                        Annuler
                    </Button>
                    <Button type="submit" variant="primary" :loading="editForm.processing">
                        Enregistrer
                    </Button>
                </div>
            </form>
        </Modal>

        <!-- Add Modal -->
        <Modal :show="addModal" title="Ajouter une traduction" @close="addModal = false">
            <form @submit.prevent="saveAdd" class="space-y-4">
                <FormGroup label="Groupe" :error="addForm.errors.group">
                    <Select v-model="addForm.group" :options="groupOptions" />
                </FormGroup>

                <FormGroup label="Clé" :error="addForm.errors.key">
                    <Input
                        v-model="addForm.key"
                        placeholder="ex: button.save"
                    />
                </FormGroup>

                <FormGroup label="Valeur" :error="addForm.errors.value">
                    <Textarea
                        v-model="addForm.value"
                        rows="3"
                        placeholder="Entrez la traduction..."
                    />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary" @click="addModal = false">
                        Annuler
                    </Button>
                    <Button type="submit" variant="primary" :loading="addForm.processing">
                        Ajouter
                    </Button>
                </div>
            </form>
        </Modal>

        <!-- Delete Modal -->
        <ConfirmModal
            :show="deleteModal"
            title="Supprimer la traduction"
            message="Cette action est irréversible. La traduction sera supprimée définitivement."
            confirm-text="Supprimer"
            variant="danger"
            @close="deleteModal = false"
            @confirm="doDelete"
        />
    </AdminLayout>
</template>
