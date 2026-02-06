<script setup>
defineProps({
    vertical: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div
        class="inline-flex"
        :class="vertical ? 'flex-col' : 'flex-row'"
        role="group"
    >
        <slot />
    </div>
</template>

<style scoped>
/* Clip ring to only show bottom edge */
:deep(button),
:deep(a) {
    clip-path: inset(2px 1px -6px 1px);
}

/* Horizontal grouping */
:not(.flex-col) > :deep(button),
:not(.flex-col) > :deep(a) {
    border-radius: 0;
}
:not(.flex-col) > :deep(button:first-child),
:not(.flex-col) > :deep(a:first-child) {
    border-top-left-radius: 0.5rem;
    border-bottom-left-radius: 0.5rem;
}
:not(.flex-col) > :deep(button:last-child),
:not(.flex-col) > :deep(a:last-child) {
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}
:not(.flex-col) > :deep(button + button),
:not(.flex-col) > :deep(a + a),
:not(.flex-col) > :deep(a + button),
:not(.flex-col) > :deep(button + a) {
    margin-left: -1px;
}

/* Vertical grouping */
.flex-col > :deep(button),
.flex-col > :deep(a) {
    border-radius: 0;
    width: 100%;
}
.flex-col > :deep(button:first-child),
.flex-col > :deep(a:first-child) {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}
.flex-col > :deep(button:last-child),
.flex-col > :deep(a:last-child) {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}
.flex-col > :deep(button + button),
.flex-col > :deep(a + a) {
    margin-top: -1px;
}
</style>
