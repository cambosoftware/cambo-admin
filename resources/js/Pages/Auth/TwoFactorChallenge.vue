<script setup>
import { ref, nextTick } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Components/Layout/GuestLayout.vue'
import Form from '@/Components/Form/Form.vue'
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
        title="Authentification à deux facteurs"
        :subtitle="recovery
            ? 'Veuillez confirmer l\'accès à votre compte en entrant un de vos codes de récupération.'
            : 'Veuillez confirmer l\'accès à votre compte en entrant le code fourni par votre application d\'authentification.'"
    >
        <Form @submit="submit" :errors="form.errors">
            <div class="space-y-5">
                <FormGroup
                    v-if="!recovery"
                    label="Code d'authentification"
                    name="code"
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
                    label="Code de récupération"
                    name="recovery_code"
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
                    Connexion
                </Button>

                <div class="text-center">
                    <button
                        type="button"
                        class="text-sm font-medium text-primary-600 hover:text-primary-500"
                        @click="toggleRecovery"
                    >
                        {{ recovery ? 'Utiliser un code d\'authentification' : 'Utiliser un code de récupération' }}
                    </button>
                </div>
            </div>
        </Form>

        <template #footer>
            <p class="text-center text-sm text-gray-600">
                <Link href="/login" class="font-medium text-primary-600 hover:text-primary-500">
                    Retour à la connexion
                </Link>
            </p>
        </template>
    </GuestLayout>
</template>
