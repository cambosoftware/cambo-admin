<script setup>
import { ref, computed } from 'vue'
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
import Switch from '@/Components/Form/Switch.vue'
import ColorPicker from '@/Components/Form/ColorPicker.vue'
import FilePicker from '@/Components/Form/FilePicker.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import {
    Cog6ToothIcon,
    PaintBrushIcon,
    EnvelopeIcon,
    ShieldCheckIcon,
    PuzzlePieceIcon,
    WrenchScrewdriverIcon,
    ArrowPathIcon,
    CheckIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    settings: Array,
    groups: Object,
    activeGroup: String,
})

// Icons for groups
const groupIcons = {
    general: Cog6ToothIcon,
    appearance: PaintBrushIcon,
    email: EnvelopeIcon,
    security: ShieldCheckIcon,
    integrations: PuzzlePieceIcon,
    advanced: WrenchScrewdriverIcon,
}

// Form state
const form = useForm({
    settings: props.settings.reduce((acc, setting) => {
        acc[setting.key] = setting.value ?? setting.default_value
        return acc
    }, {})
})

// Modal
const resetModal = ref(false)

// Computed
const hasChanges = computed(() => form.isDirty)

// Actions
const selectGroup = (group) => {
    router.get('/settings', { group }, { preserveState: true })
}

const saveSettings = () => {
    form.put('/settings', {
        preserveScroll: true,
    })
}

const confirmReset = () => {
    router.post('/settings/reset-group', {
        group: props.activeGroup
    }, {
        onSuccess: () => {
            resetModal.value = false
        }
    })
}

// Render input based on type
const getInputComponent = (type) => {
    const components = {
        text: Input,
        textarea: Textarea,
        number: Input,
        boolean: Switch,
        select: Select,
        multiselect: Select,
        color: ColorPicker,
        file: FilePicker,
    }
    return components[type] || Input
}

const getInputProps = (setting) => {
    const baseProps = {
        id: setting.key,
    }

    switch (setting.type) {
        case 'number':
            return { ...baseProps, type: 'number' }
        case 'select':
        case 'multiselect':
            return {
                ...baseProps,
                options: setting.options || [],
                multiple: setting.type === 'multiselect'
            }
        case 'boolean':
            return { ...baseProps }
        default:
            return baseProps
    }
}
</script>

<template>
    <AdminLayout title="Paramètres">
        <PageHeader
            title="Paramètres"
            subtitle="Configuration de l'application"
        >
            <template #actions>
                <Button
                    v-if="hasChanges"
                    variant="primary"
                    :icon-left="CheckIcon"
                    :loading="form.processing"
                    @click="saveSettings"
                >
                    Enregistrer
                </Button>
            </template>
        </PageHeader>

        <div class="flex gap-6">
            <!-- Sidebar -->
            <div class="w-64 flex-shrink-0">
                <Card>
                    <nav class="p-2 space-y-1">
                        <button
                            v-for="(group, key) in groups"
                            :key="key"
                            :class="[
                                'w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors',
                                activeGroup === key
                                    ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                            ]"
                            @click="selectGroup(key)"
                        >
                            <component
                                :is="groupIcons[key]"
                                class="h-5 w-5 flex-shrink-0"
                            />
                            <span class="flex-1 text-sm font-medium">{{ group.label }}</span>
                            <Badge v-if="group.count" variant="secondary" size="sm">
                                {{ group.count }}
                            </Badge>
                        </button>
                    </nav>
                </Card>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <!-- Group header -->
                <Card class="mb-6">
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <component :is="groupIcons[activeGroup]" class="h-6 w-6 text-indigo-600" />
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ groups[activeGroup]?.label }}
                                </h2>
                                <p class="text-sm text-gray-500">
                                    {{ groups[activeGroup]?.description }}
                                </p>
                            </div>
                        </div>

                        <Button
                            variant="ghost"
                            size="sm"
                            :icon-left="ArrowPathIcon"
                            @click="resetModal = true"
                        >
                            Réinitialiser
                        </Button>
                    </div>
                </Card>

                <!-- Flash message -->
                <Alert
                    v-if="$page.props.flash?.success"
                    variant="success"
                    dismissible
                    class="mb-6"
                >
                    {{ $page.props.flash.success }}
                </Alert>

                <!-- Settings form -->
                <Card>
                    <form @submit.prevent="saveSettings" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div
                            v-for="setting in settings"
                            :key="setting.key"
                            class="p-6"
                        >
                            <div class="flex items-start gap-6">
                                <div class="flex-1">
                                    <label
                                        :for="setting.key"
                                        class="block text-sm font-medium text-gray-900 dark:text-white mb-1"
                                    >
                                        {{ setting.label }}
                                    </label>
                                    <p
                                        v-if="setting.description"
                                        class="text-sm text-gray-500 mb-3"
                                    >
                                        {{ setting.description }}
                                    </p>

                                    <!-- Boolean (Switch) -->
                                    <Switch
                                        v-if="setting.type === 'boolean'"
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                    />

                                    <!-- Select -->
                                    <Select
                                        v-else-if="setting.type === 'select' || setting.type === 'multiselect'"
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                        :options="setting.options || []"
                                        :multiple="setting.type === 'multiselect'"
                                        class="max-w-md"
                                    />

                                    <!-- Color -->
                                    <ColorPicker
                                        v-else-if="setting.type === 'color'"
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                    />

                                    <!-- Textarea -->
                                    <Textarea
                                        v-else-if="setting.type === 'textarea'"
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                        rows="3"
                                        class="max-w-md"
                                    />

                                    <!-- Number -->
                                    <Input
                                        v-else-if="setting.type === 'number'"
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                        type="number"
                                        class="max-w-xs"
                                    />

                                    <!-- File -->
                                    <FilePicker
                                        v-else-if="setting.type === 'file'"
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                        class="max-w-md"
                                    />

                                    <!-- Default (text) -->
                                    <Input
                                        v-else
                                        v-model="form.settings[setting.key]"
                                        :id="setting.key"
                                        :type="setting.is_encrypted ? 'password' : 'text'"
                                        class="max-w-md"
                                    />

                                    <!-- Error -->
                                    <p
                                        v-if="form.errors[`settings.${setting.key}`]"
                                        class="mt-2 text-sm text-red-600"
                                    >
                                        {{ form.errors[`settings.${setting.key}`] }}
                                    </p>
                                </div>

                                <!-- Badges -->
                                <div class="flex flex-col gap-1">
                                    <Badge v-if="setting.is_public" variant="info" size="sm">
                                        Public
                                    </Badge>
                                    <Badge v-if="setting.is_encrypted" variant="warning" size="sm">
                                        Chiffré
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <Button
                            variant="primary"
                            :icon-left="CheckIcon"
                            :loading="form.processing"
                            :disabled="!hasChanges"
                            @click="saveSettings"
                        >
                            Enregistrer les modifications
                        </Button>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Reset Confirmation -->
        <ConfirmModal
            :show="resetModal"
            title="Réinitialiser les paramètres"
            message="Cette action réinitialisera tous les paramètres de ce groupe à leurs valeurs par défaut. Cette action est irréversible."
            confirm-text="Réinitialiser"
            variant="danger"
            @close="resetModal = false"
            @confirm="confirmReset"
        />
    </AdminLayout>
</template>
