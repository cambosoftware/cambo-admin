<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import Card from '@/Components/Containers/Card.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import Button from '@/Components/UI/Button.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import Badge from '@/Components/UI/Badge.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import ClickToCopy from '@/Components/Utilities/ClickToCopy.vue'
import { ShieldCheckIcon, KeyIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    secret: {
        type: String,
        default: ''
    },
    qrCodeUrl: {
        type: String,
        default: ''
    },
    enabled: {
        type: Boolean,
        default: false
    },
    recoveryCodes: {
        type: Array,
        default: () => []
    }
})

const showDisableModal = ref(false)
const showRegenerateModal = ref(false)

const enableForm = useForm({
    code: ''
})

const disableForm = useForm({
    password: ''
})

const regenerateForm = useForm({
    password: ''
})

const qrCodeSvg = computed(() => {
    if (!props.qrCodeUrl) return ''
    // Generate QR code URL for display
    return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(props.qrCodeUrl)}`
})

const enable = () => {
    enableForm.post('/two-factor/enable', {
        preserveScroll: true,
        onSuccess: () => {
            enableForm.reset()
        }
    })
}

const disable = () => {
    disableForm.delete('/two-factor/disable', {
        preserveScroll: true,
        onSuccess: () => {
            showDisableModal.value = false
            disableForm.reset()
        }
    })
}

const regenerateRecoveryCodes = () => {
    regenerateForm.post('/two-factor/recovery-codes', {
        preserveScroll: true,
        onSuccess: () => {
            showRegenerateModal.value = false
            regenerateForm.reset()
        }
    })
}
</script>

<template>
    <AdminLayout
        title="Authentification à deux facteurs"
        :breadcrumb="[
            { label: 'Profil', href: '/profile' },
            { label: 'Sécurité 2FA' }
        ]"
    >
        <div class="max-w-2xl space-y-6">
            <!-- Status Card -->
            <Card>
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full"
                        :class="enabled ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'"
                    >
                        <ShieldCheckIcon class="h-6 w-6" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Authentification à deux facteurs
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ enabled ? 'Activée - Votre compte est protégé' : 'Désactivée - Activez pour plus de sécurité' }}
                        </p>
                    </div>
                    <Badge :variant="enabled ? 'success' : 'warning'">
                        {{ enabled ? 'Activée' : 'Désactivée' }}
                    </Badge>
                </div>
            </Card>

            <!-- Setup Card (when not enabled) -->
            <Card v-if="!enabled" title="Configuration">
                <div class="space-y-6">
                    <Alert variant="info">
                        <p>
                            L'authentification à deux facteurs ajoute une couche de sécurité supplémentaire à votre compte.
                            Vous devrez scanner le QR code ci-dessous avec une application comme Google Authenticator ou Authy.
                        </p>
                    </Alert>

                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                        <!-- QR Code -->
                        <div class="flex flex-col items-center gap-3">
                            <div class="rounded-lg border bg-white p-4">
                                <img
                                    v-if="qrCodeSvg"
                                    :src="qrCodeSvg"
                                    alt="QR Code"
                                    class="h-48 w-48"
                                />
                            </div>
                            <p class="text-xs text-gray-500">Scannez avec votre app 2FA</p>
                        </div>

                        <!-- Manual setup -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-700">
                                    Ou entrez ce code manuellement :
                                </p>
                                <div class="rounded-md bg-gray-50 p-3">
                                    <ClickToCopy
                                        :text="secret"
                                        class="font-mono text-sm font-semibold tracking-wider text-gray-900"
                                    />
                                </div>
                            </div>

                            <form @submit.prevent="enable" class="space-y-4">
                                <FormGroup
                                    label="Code de vérification"
                                    name="code"
                                    hint="Entrez le code à 6 chiffres de votre application"
                                    required
                                >
                                    <Input
                                        v-model="enableForm.code"
                                        type="text"
                                        inputmode="numeric"
                                        placeholder="000000"
                                        maxlength="6"
                                        :error="enableForm.errors.code"
                                    />
                                </FormGroup>

                                <Button
                                    type="submit"
                                    variant="primary"
                                    :loading="enableForm.processing"
                                >
                                    Activer la 2FA
                                </Button>
                            </form>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Recovery Codes (when enabled) -->
            <Card v-if="enabled" title="Codes de récupération">
                <template #actions>
                    <Button
                        variant="ghost"
                        size="sm"
                        @click="showRegenerateModal = true"
                    >
                        Régénérer
                    </Button>
                </template>

                <div class="space-y-4">
                    <Alert variant="warning" :icon="ExclamationTriangleIcon">
                        <p>
                            Conservez ces codes dans un endroit sûr. Ils vous permettront d'accéder à votre compte
                            si vous perdez l'accès à votre application d'authentification.
                        </p>
                    </Alert>

                    <div class="grid grid-cols-2 gap-2 rounded-md bg-gray-50 p-4">
                        <div
                            v-for="code in recoveryCodes"
                            :key="code"
                            class="font-mono text-sm"
                        >
                            <ClickToCopy :text="code" />
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Disable Card (when enabled) -->
            <Card v-if="enabled" title="Désactiver la 2FA">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Désactiver l'authentification à deux facteurs rendra votre compte moins sécurisé.
                    </p>
                    <Button
                        variant="danger"
                        @click="showDisableModal = true"
                    >
                        Désactiver
                    </Button>
                </div>
            </Card>
        </div>

        <!-- Disable Modal -->
        <ConfirmModal
            v-model="showDisableModal"
            title="Désactiver la 2FA"
            message="Êtes-vous sûr de vouloir désactiver l'authentification à deux facteurs ? Votre compte sera moins sécurisé."
            confirm-text="Désactiver"
            variant="danger"
            :loading="disableForm.processing"
            @confirm="disable"
        >
            <FormGroup label="Mot de passe" name="password" required class="mt-4">
                <PasswordInput
                    v-model="disableForm.password"
                    :error="disableForm.errors.password"
                />
            </FormGroup>
        </ConfirmModal>

        <!-- Regenerate Modal -->
        <ConfirmModal
            v-model="showRegenerateModal"
            title="Régénérer les codes"
            message="Les anciens codes de récupération seront invalidés. Assurez-vous de sauvegarder les nouveaux codes."
            confirm-text="Régénérer"
            variant="warning"
            :loading="regenerateForm.processing"
            @confirm="regenerateRecoveryCodes"
        >
            <FormGroup label="Mot de passe" name="password" required class="mt-4">
                <PasswordInput
                    v-model="regenerateForm.password"
                    :error="regenerateForm.errors.password"
                />
            </FormGroup>
        </ConfirmModal>
    </AdminLayout>
</template>
