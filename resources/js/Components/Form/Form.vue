<script setup>
import { provide, computed } from 'vue'
import { useForm as useInertiaForm } from '@inertiajs/vue3'

const props = defineProps({
    method: {
        type: String,
        default: 'post',
        validator: (v) => ['get', 'post', 'put', 'patch', 'delete'].includes(v)
    },
    action: {
        type: String,
        default: null
    },
    form: {
        type: Object,
        default: null
    },
    preventSubmit: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['submit'])

const errors = computed(() => props.form?.errors ?? {})
const processing = computed(() => props.form?.processing ?? false)

provide('formErrors', errors)
provide('formProcessing', processing)

const onSubmit = () => {
    if (props.preventSubmit) return
    emit('submit', props.form)
}
</script>

<template>
    <form @submit.prevent="onSubmit" class="space-y-4">
        <slot :form="form" :errors="errors" :processing="processing" />
    </form>
</template>
