<script setup>
import { ref, nextTick } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Button from '@/Components/UI/Button.vue'

const recovery = ref(false)
const codeInput = ref(null)
const recoveryCodeInput = ref(null)

const form = useForm({
    code: '',
    recovery_code: ''
})

const toggleRecovery = async () => {
    recovery.value = !recovery.value

    await nextTick()

    if (recovery.value) {
        form.code = ''
        recoveryCodeInput.value?.focus()
    } else {
        form.recovery_code = ''
        codeInput.value?.focus()
    }
}

const submit = () => {
    form.post('/two-factor-challenge', {
        onFinish: () => form.reset()
    })
}
</script>

<template>
    <GuestLayout
        title="Two-Factor Authentication"
        :subtitle="recovery
            ? 'Please confirm access to your account by entering one of your recovery codes.'
            : 'Please confirm access to your account by entering the code provided by your authenticator app.'"
    >
        <form @submit.prevent="submit" class="space-y-5">
            <FormGroup
                v-if="!recovery"
                label="Authentication Code"
                :error="form.errors.code"
                required
            >
                <Input
                    ref="codeInput"
                    v-model="form.code"
                    type="text"
                    inputmode="numeric"
                    placeholder="000000"
                    autocomplete="one-time-code"
                    :error="form.errors.code"
                    autofocus
                />
            </FormGroup>

            <FormGroup
                v-else
                label="Recovery Code"
                :error="form.errors.recovery_code"
                required
            >
                <Input
                    ref="recoveryCodeInput"
                    v-model="form.recovery_code"
                    type="text"
                    placeholder="XXXXXXXXXX"
                    autocomplete="off"
                    :error="form.errors.recovery_code"
                    autofocus
                />
            </FormGroup>

            <Button
                type="submit"
                variant="primary"
                class="w-full"
                size="lg"
                :loading="form.processing"
            >
                Sign In
            </Button>

            <div class="text-center">
                <button
                    type="button"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                    @click="toggleRecovery"
                >
                    {{ recovery ? 'Use an authentication code' : 'Use a recovery code' }}
                </button>
            </div>
        </form>

        <template #footer>
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                <Link href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Back to Sign In
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
