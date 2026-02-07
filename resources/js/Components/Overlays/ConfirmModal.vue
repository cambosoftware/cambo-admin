<script setup>
import { computed } from 'vue'
import Modal from './Modal.vue'
import Button from '../UI/Button.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Confirmation'
    },
    message: {
        type: String,
        default: 'Etes-vous sur de vouloir continuer ?'
    },
    variant: {
        type: String,
        default: 'danger',
        validator: (v) => ['danger', 'warning', 'primary'].includes(v)
    },
    confirmLabel: {
        type: String,
        default: 'Confirmer'
    },
    cancelLabel: {
        type: String,
        default: 'Annuler'
    },
    loading: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['confirm', 'cancel', 'update:show'])

const iconColor = computed(() => {
    const colors = {
        danger: 'text-red-600 bg-red-100',
        warning: 'text-amber-600 bg-amber-100',
        primary: 'text-indigo-600 bg-indigo-100'
    }
    return colors[props.variant]
})

const cancel = () => {
    emit('cancel')
    emit('update:show', false)
}

const confirm = () => {
    emit('confirm')
}
</script>

<template>
    <Modal
        :show="show"
        size="sm"
        :closable="!loading"
        :close-on-overlay="!loading"
        :close-on-escape="!loading"
        @close="cancel"
    >
        <div class="text-center sm:text-left">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                <!-- Icon -->
                <div :class="['flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full', iconColor]">
                    <!-- Danger / Warning icon -->
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ title }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        <slot>{{ message }}</slot>
                    </p>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                <Button
                    variant="secondary"
                    :disabled="loading"
                    @click="cancel"
                >
                    {{ cancelLabel }}
                </Button>
                <Button
                    :variant="variant"
                    :loading="loading"
                    @click="confirm"
                >
                    {{ confirmLabel }}
                </Button>
            </div>
        </template>
    </Modal>
</template>
