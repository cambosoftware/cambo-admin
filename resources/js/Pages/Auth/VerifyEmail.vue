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
        title="Verify Email"
        subtitle="Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent you."
    >
        <Alert v-if="verificationLinkSent" variant="success" class="mb-6">
            A new verification link has been sent to the email address you provided during registration.
        </Alert>

        <form @submit.prevent="submit">
            <div class="flex flex-col gap-4">
                <Button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    :loading="form.processing"
                >
                    Resend Verification Email
                </Button>

                <div class="text-center">
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        Sign Out
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
