<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Button from '@/Components/UI/Button.vue'
import Alert from '@/Components/Feedback/Alert.vue'

const props = defineProps({
    status: {
        type: String,
        default: null
    }
})

const form = useForm({})

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')

const submit = () => {
    form.post('/email/verification-notification')
}
</script>

<template>
    <GuestLayout
        title="Vérification de l'email"
        subtitle="Merci de votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer."
    >
        <Alert v-if="verificationLinkSent" variant="success" class="mb-6">
            Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
        </Alert>

        <form @submit.prevent="submit">
            <div class="flex flex-col gap-4">
                <Button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    :loading="form.processing"
                >
                    Renvoyer l'email de vérification
                </Button>

                <div class="text-center">
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900"
                    >
                        Se déconnecter
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
