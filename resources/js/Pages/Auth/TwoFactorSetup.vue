<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
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
import { ShieldCheckIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

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
        title="Two-Factor Authentication"
        :breadcrumb="[
            { label: 'Profile', href: '/profile' },
            { label: '2FA Security' }
        ]"
    >
        <div class="max-w-2xl space-y-6">
            <!-- Status Card -->
            <Card>
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full"
                        :class="enabled ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500'"
                    >
                        <ShieldCheckIcon class="h-6 w-6" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Two-Factor Authentication
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ enabled ? 'Enabled - Your account is protected' : 'Disabled - Enable for extra security' }}
                        </p>
                    </div>
                    <Badge :variant="enabled ? 'success' : 'warning'">
                        {{ enabled ? 'Enabled' : 'Disabled' }}
                    </Badge>
                </div>
            </Card>

            <!-- Setup Card (when not enabled) -->
            <Card v-if="!enabled" title="Setup">
                <div class="space-y-6">
                    <Alert variant="info">
                        <p>
                            Two-factor authentication adds an extra layer of security to your account.
                            You will need to scan the QR code below with an app like Google Authenticator or Authy.
                        </p>
                    </Alert>

                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                        <!-- QR Code -->
                        <div class="flex flex-col items-center gap-3">
                            <div class="rounded-lg border bg-white p-4 dark:border-gray-700">
                                <img
                                    v-if="qrCodeSvg"
                                    :src="qrCodeSvg"
                                    alt="QR Code"
                                    class="h-48 w-48"
                                />
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Scan with your 2FA app</p>
                        </div>

                        <!-- Manual setup -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Or enter this code manually:
                                </p>
                                <div class="rounded-md bg-gray-50 p-3 dark:bg-gray-800">
                                    <ClickToCopy
                                        :text="secret"
                                        class="font-mono text-sm font-semibold tracking-wider text-gray-900 dark:text-white"
                                    />
                                </div>
                            </div>

                            <form @submit.prevent="enable" class="space-y-4">
                                <FormGroup
                                    label="Verification Code"
                                    :error="enableForm.errors.code"
                                    hint="Enter the 6-digit code from your app"
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
                                    Enable 2FA
                                </Button>
                            </form>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Recovery Codes (when enabled) -->
            <Card v-if="enabled" title="Recovery Codes">
                <template #actions>
                    <Button
                        variant="ghost"
                        size="sm"
                        @click="showRegenerateModal = true"
                    >
                        Regenerate
                    </Button>
                </template>

                <div class="space-y-4">
                    <Alert variant="warning" :icon="ExclamationTriangleIcon">
                        <p>
                            Store these codes in a safe place. They will allow you to access your account
                            if you lose access to your authenticator app.
                        </p>
                    </Alert>

                    <div class="grid grid-cols-2 gap-2 rounded-md bg-gray-50 p-4 dark:bg-gray-800">
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
            <Card v-if="enabled" title="Disable 2FA">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Disabling two-factor authentication will make your account less secure.
                    </p>
                    <Button
                        variant="danger"
                        @click="showDisableModal = true"
                    >
                        Disable
                    </Button>
                </div>
            </Card>
        </div>

        <!-- Disable Modal -->
        <ConfirmModal
            v-model="showDisableModal"
            title="Disable 2FA"
            message="Are you sure you want to disable two-factor authentication? Your account will be less secure."
            confirm-text="Disable"
            variant="danger"
            :loading="disableForm.processing"
            @confirm="disable"
        >
            <FormGroup label="Password" :error="disableForm.errors.password" required class="mt-4">
                <PasswordInput
                    v-model="disableForm.password"
                    :error="disableForm.errors.password"
                />
            </FormGroup>
        </ConfirmModal>

        <!-- Regenerate Modal -->
        <ConfirmModal
            v-model="showRegenerateModal"
            title="Regenerate Codes"
            message="Old recovery codes will be invalidated. Make sure to save the new codes."
            confirm-text="Regenerate"
            variant="warning"
            :loading="regenerateForm.processing"
            @confirm="regenerateRecoveryCodes"
        >
            <FormGroup label="Password" :error="regenerateForm.errors.password" required class="mt-4">
                <PasswordInput
                    v-model="regenerateForm.password"
                    :error="regenerateForm.errors.password"
                />
            </FormGroup>
        </ConfirmModal>
    </AdminLayout>
</template>
