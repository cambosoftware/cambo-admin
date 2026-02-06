<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineProps({
    disabled: {
        type: Boolean,
        default: false
    }
})

const open = ref(false)
const x = ref(0)
const y = ref(0)
const menuRef = ref(null)

const onContextMenu = (e) => {
    e.preventDefault()
    x.value = e.clientX
    y.value = e.clientY
    open.value = true
}

const close = () => {
    open.value = false
}

const onClickOutside = (e) => {
    if (menuRef.value && !menuRef.value.contains(e.target)) {
        open.value = false
    }
}

const onEscape = (e) => {
    if (e.key === 'Escape') open.value = false
}

onMounted(() => {
    document.addEventListener('click', onClickOutside)
    document.addEventListener('keydown', onEscape)
})

onUnmounted(() => {
    document.removeEventListener('click', onClickOutside)
    document.removeEventListener('keydown', onEscape)
})
</script>

<template>
    <div @contextmenu="!disabled && onContextMenu($event)">
        <!-- Target area -->
        <slot name="target" />

        <!-- Context menu -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="open"
                    ref="menuRef"
                    class="fixed z-50 min-w-48 rounded-lg bg-white shadow-lg ring-1 ring-black/5 py-1 overflow-hidden"
                    :style="{ left: x + 'px', top: y + 'px' }"
                    role="menu"
                >
                    <slot :close="close" />
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
