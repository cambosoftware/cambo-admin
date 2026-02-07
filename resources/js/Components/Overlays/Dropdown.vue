<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'

const props = defineProps({
    align: {
        type: String,
        default: 'left',
        validator: (v) => ['left', 'right'].includes(v)
    },
    position: {
        type: String,
        default: 'bottom-start',
        validator: (v) => ['bottom-start', 'bottom-end', 'top-start', 'top-end'].includes(v)
    },
    width: {
        type: String,
        default: '48',
        validator: (v) => ['32', '40', '48', '56', '64', '72', '80'].includes(v)
    },
    trigger: {
        type: String,
        default: 'click',
        validator: (v) => ['click', 'hover'].includes(v)
    },
    /**
     * Use teleport to render dropdown at body level
     * This prevents clipping by parent overflow
     */
    teleport: {
        type: Boolean,
        default: true
    }
})

const open = ref(false)
const dropdownRef = ref(null)
const triggerRef = ref(null)
const menuRef = ref(null)
const menuStyle = ref({})
let hoverTimeout = null

const widthClass = computed(() => `w-${props.width}`)

// Compute alignment from position prop or align prop (for backwards compatibility)
const effectiveAlign = computed(() => {
    if (props.position.endsWith('-end')) return 'right'
    if (props.position.endsWith('-start')) return 'left'
    return props.align
})

const alignClass = computed(() => effectiveAlign.value === 'right' ? 'right-0' : 'left-0')

const updateMenuPosition = async () => {
    if (!props.teleport || !triggerRef.value) return

    await nextTick()

    const trigger = triggerRef.value
    const rect = trigger.getBoundingClientRect()
    const scrollY = window.scrollY
    const scrollX = window.scrollX

    const style = {
        position: 'absolute',
        zIndex: 9999,
    }

    // Vertical position
    if (props.position.startsWith('top')) {
        style.bottom = `${window.innerHeight - rect.top - scrollY}px`
    } else {
        style.top = `${rect.bottom + scrollY + 8}px`
    }

    // Horizontal position
    if (props.position.endsWith('-end') || effectiveAlign.value === 'right') {
        style.right = `${window.innerWidth - rect.right - scrollX}px`
    } else {
        style.left = `${rect.left + scrollX}px`
    }

    menuStyle.value = style
}

const toggle = () => {
    if (props.trigger === 'click') {
        open.value = !open.value
    }
}

const closeDropdown = () => {
    open.value = false
}

const onMouseEnter = () => {
    if (props.trigger !== 'hover') return
    clearTimeout(hoverTimeout)
    open.value = true
}

const onMouseLeave = () => {
    if (props.trigger !== 'hover') return
    hoverTimeout = setTimeout(() => { open.value = false }, 150)
}

const onClickOutside = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        // Also check if click is inside the teleported menu
        if (menuRef.value && menuRef.value.contains(e.target)) return
        open.value = false
    }
}

const onScroll = () => {
    if (open.value && props.teleport) {
        updateMenuPosition()
    }
}

const onResize = () => {
    if (open.value && props.teleport) {
        updateMenuPosition()
    }
}

watch(open, async (isOpen) => {
    if (isOpen && props.teleport) {
        await updateMenuPosition()
    }
})

onMounted(() => {
    document.addEventListener('click', onClickOutside)
    if (props.teleport) {
        window.addEventListener('scroll', onScroll, true)
        window.addEventListener('resize', onResize)
    }
})

onUnmounted(() => {
    document.removeEventListener('click', onClickOutside)
    if (props.teleport) {
        window.removeEventListener('scroll', onScroll, true)
        window.removeEventListener('resize', onResize)
    }
    clearTimeout(hoverTimeout)
})
</script>

<template>
    <div
        ref="dropdownRef"
        class="relative inline-block"
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
    >
        <!-- Trigger -->
        <div ref="triggerRef" @click="toggle" class="cursor-pointer">
            <slot name="trigger" :open="open" />
        </div>

        <!-- Menu (teleported to body) -->
        <Teleport to="body" :disabled="!teleport">
            <Transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="open"
                    ref="menuRef"
                    :class="[
                        'rounded-lg bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-900/50 ring-1 ring-black/5 dark:ring-gray-700 py-1 overflow-hidden',
                        widthClass,
                        teleport ? '' : ['absolute mt-2', alignClass]
                    ]"
                    :style="teleport ? menuStyle : {}"
                    role="menu"
                    @mouseenter="onMouseEnter"
                    @mouseleave="onMouseLeave"
                >
                    <slot :close="closeDropdown" />
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
