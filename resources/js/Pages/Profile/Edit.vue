<script setup>
import { ref } from 'vue'
import { useForm, usePage, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import ImagePicker from '@/Components/Form/ImagePicker.vue'
import Button from '@/Components/UI/Button.vue'
import Alert from '@/Components/Feedback/Alert.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import Badge from '@/Components/UI/Badge.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import {
    ShieldCheckIcon,
    ComputerDesktopIcon,
    DevicePhoneMobileIcon,
    GlobeAltIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    mustVerifyEmail: {
        type: Boolean,
        default: false
    },
    status: {
        type: String,
        default: null
    },
    sessions: {
        type: Array,
        default: () => []
    },
    twoFactorEnabled: {
        type: Boolean,
        default: false
    }
})

// Profile form
const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    avatar: null
})

const updateProfile = () => {
    profileForm.patch('/profile', {
        preserveScroll: true
    })
}

// Password form
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
})

const updatePassword = () => {
    passwordForm.put('/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset()
    })
}

// Sessions
const showLogoutOthersModal = ref(false)
const logoutOthersForm = useForm({
    password: ''
})

const logoutOtherSessions = () => {
    logoutOthersForm.delete('/sessions', {
        preserveScroll: true,
        onSuccess: () => {
            showLogoutOthersModal.value = false
            logoutOthersForm.reset()
        }
    })
}

const getDeviceIcon = (session) => {
    if (session.agent?.is_desktop) {
        return ComputerDesktopIcon
    }
    return DevicePhoneMobileIcon
}

// Delete account
const showDeleteConfirm = ref(false)
const deleteForm = useForm({
    password: ''
})

const deleteAccount = () => {
    deleteForm.delete('/profile', {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false
        }
    })
}
</script>

<template>
    <AdminLayout title="Mon profil">
        <PageHeader
            title="Mon profil"
            subtitle="Gérez vos informations personnelles et votre sécurité"
        />

        <div class="max-w-3xl space-y-6">
            <!-- Profile Information -->
            <Card title="Informations personnelles" subtitle="Mettez à jour vos informations de profil">
                <Form @submit="updateProfile" :errors="profileForm.errors">
                    <div class="space-y-5">
                        <!-- Avatar -->
                        <div class="flex items-center gap-4">
                            <Avatar
                                :src="user.avatar_url"
                                :name="user.name"
                                size="xl"
                            />
                            <div>
                                <ImagePicker
                                    v-model="profileForm.avatar"
                                    accept="image/*"
                                    :max-size="2"
                                >
                                    <Button variant="secondary" size="sm">
                                        Changer la photo
                                    </Button>
                                </ImagePicker>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG. Max 2Mo</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <FormGroup label="Nom complet" name="name" required>
                                <Input
                                    v-model="profileForm.name"
                                    type="text"
                                    autocomplete="name"
                                    :error="profileForm.errors.name"
                                />
                            </FormGroup>

                            <FormGroup label="Adresse email" name="email" required>
                                <Input
                                    v-model="profileForm.email"
                                    type="email"
                                    autocomplete="email"
                                    :error="profileForm.errors.email"
                                />
                            </FormGroup>
                        </div>

                        <Alert v-if="mustVerifyEmail && !user.email_verified_at" variant="warning">
                            Votre adresse email n'est pas vérifiée.
                            <button type="button" class="underline" @click="router.post('/email/verification-notification')">
                                Renvoyer l'email de vérification
                            </button>
                        </Alert>

                        <Alert v-if="status === 'verification-link-sent'" variant="success">
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </Alert>

                        <Alert v-if="status === 'profile-updated'" variant="success">
                            Profil mis à jour avec succès.
                        </Alert>

                        <div class="flex justify-end">
                            <Button
                                type="submit"
                                variant="primary"
                                :loading="profileForm.processing"
                            >
                                Enregistrer
                            </Button>
                        </div>
                    </div>
                </Form>
            </Card>

            <!-- Update Password -->
            <Card title="Mot de passe" subtitle="Assurez-vous d'utiliser un mot de passe long et aléatoire">
                <Form @submit="updatePassword" :errors="passwordForm.errors">
                    <div class="space-y-5">
                        <Alert v-if="status === 'password-updated'" variant="success">
                            Mot de passe mis à jour avec succès.
                        </Alert>

                        <FormGroup label="Mot de passe actuel" name="current_password" required>
                            <PasswordInput
                                v-model="passwordForm.current_password"
                                autocomplete="current-password"
                                :error="passwordForm.errors.current_password"
                            />
                        </FormGroup>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <FormGroup label="Nouveau mot de passe" name="password" required>
                                <PasswordInput
                                    v-model="passwordForm.password"
                                    autocomplete="new-password"
                                    :error="passwordForm.errors.password"
                                />
                            </FormGroup>

                            <FormGroup label="Confirmer le mot de passe" name="password_confirmation" required>
                                <PasswordInput
                                    v-model="passwordForm.password_confirmation"
                                    autocomplete="new-password"
                                    :error="passwordForm.errors.password_confirmation"
                                />
                            </FormGroup>
                        </div>

                        <div class="flex justify-end">
                            <Button
                                type="submit"
                                variant="primary"
                                :loading="passwordForm.processing"
                            >
                                Mettre à jour
                            </Button>
                        </div>
                    </div>
                </Form>
            </Card>

            <!-- Two-Factor Authentication -->
            <Card title="Authentification à deux facteurs" subtitle="Ajoutez une couche de sécurité supplémentaire">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full"
                            :class="twoFactorEnabled ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'"
                        >
                            <ShieldCheckIcon class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ twoFactorEnabled ? 'Activée' : 'Désactivée' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ twoFactorEnabled
                                    ? 'Votre compte est protégé par l\'authentification à deux facteurs.'
                                    : 'Protégez votre compte avec l\'authentification à deux facteurs.'
                                }}
                            </p>
                        </div>
                    </div>

                    <Link href="/two-factor">
                        <Button :variant="twoFactorEnabled ? 'secondary' : 'primary'">
                            {{ twoFactorEnabled ? 'Gérer' : 'Activer' }}
                        </Button>
                    </Link>
                </div>
            </Card>

            <!-- Browser Sessions -->
            <Card
                v-if="sessions.length > 0"
                title="Sessions actives"
                subtitle="Gérez vos sessions actives sur d'autres navigateurs et appareils"
            >
                <div class="space-y-4">
                    <Alert v-if="status === 'other-sessions-deleted'" variant="success">
                        Toutes les autres sessions ont été déconnectées.
                    </Alert>

                    <div class="space-y-3">
                        <div
                            v-for="session in sessions"
                            :key="session.id"
                            class="flex items-center gap-4"
                        >
                            <component
                                :is="getDeviceIcon(session)"
                                class="h-8 w-8 text-gray-400"
                            />
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">
                                        {{ session.agent?.platform || 'Inconnu' }} - {{ session.agent?.browser || 'Inconnu' }}
                                    </span>
                                    <Badge v-if="session.is_current_device" variant="success" size="sm">
                                        Cette session
                                    </Badge>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ session.ip_address }} - {{ session.last_active }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t">
                        <Button
                            variant="secondary"
                            @click="showLogoutOthersModal = true"
                        >
                            Déconnecter les autres sessions
                        </Button>
                    </div>
                </div>
            </Card>

            <!-- Delete Account -->
            <Card title="Supprimer le compte" subtitle="Supprimez définitivement votre compte">
                <p class="text-sm text-gray-600 mb-4">
                    Une fois votre compte supprimé, toutes vos ressources et données seront définitivement effacées.
                    Avant de supprimer votre compte, veuillez télécharger les données que vous souhaitez conserver.
                </p>

                <Button
                    v-if="!showDeleteConfirm"
                    variant="danger"
                    @click="showDeleteConfirm = true"
                >
                    Supprimer mon compte
                </Button>

                <Form v-else @submit="deleteAccount" :errors="deleteForm.errors">
                    <div class="space-y-4">
                        <Alert variant="danger">
                            Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.
                        </Alert>

                        <FormGroup label="Confirmez votre mot de passe" name="password" required>
                            <PasswordInput
                                v-model="deleteForm.password"
                                :error="deleteForm.errors.password"
                            />
                        </FormGroup>

                        <div class="flex gap-3">
                            <Button
                                type="button"
                                variant="secondary"
                                @click="showDeleteConfirm = false"
                            >
                                Annuler
                            </Button>
                            <Button
                                type="submit"
                                variant="danger"
                                :loading="deleteForm.processing"
                            >
                                Supprimer définitivement
                            </Button>
                        </div>
                    </div>
                </Form>
            </Card>
        </div>

        <!-- Logout Others Modal -->
        <ConfirmModal
            v-model="showLogoutOthersModal"
            title="Déconnecter les autres sessions"
            message="Êtes-vous sûr de vouloir déconnecter toutes vos autres sessions ? Vous devrez vous reconnecter sur ces appareils."
            confirm-text="Déconnecter"
            variant="warning"
            :loading="logoutOthersForm.processing"
            @confirm="logoutOtherSessions"
        >
            <FormGroup label="Mot de passe" name="password" required class="mt-4">
                <PasswordInput
                    v-model="logoutOthersForm.password"
                    :error="logoutOthersForm.errors.password"
                />
            </FormGroup>
        </ConfirmModal>
    </AdminLayout>
</template>
