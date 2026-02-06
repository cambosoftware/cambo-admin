<script setup>
import { ref, computed } from 'vue'
import { PlusIcon, EllipsisHorizontalIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    /**
     * Array of columns
     * Each column: { id, title, color?, items: KanbanItem[] }
     * KanbanItem: { id, title, description?, labels?, assignees?, dueDate?, priority? }
     */
    columns: {
        type: Array,
        required: true
    },
    /**
     * Allow adding new cards
     */
    allowAdd: {
        type: Boolean,
        default: true
    },
    /**
     * Allow adding new columns
     */
    allowAddColumn: {
        type: Boolean,
        default: false
    },
    /**
     * Card click handler
     */
    cardClickable: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['card-click', 'card-move', 'card-add', 'column-add', 'card-menu', 'column-menu'])

// Drag state
const draggedCard = ref(null)
const draggedFromColumn = ref(null)
const dropTargetColumn = ref(null)
const dropTargetIndex = ref(null)

// Priority colors
const priorityColors = {
    low: 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
    medium: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    high: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
    urgent: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
}

// Label colors
const labelColors = [
    'bg-red-500',
    'bg-orange-500',
    'bg-amber-500',
    'bg-emerald-500',
    'bg-teal-500',
    'bg-cyan-500',
    'bg-blue-500',
    'bg-indigo-500',
    'bg-violet-500',
    'bg-pink-500'
]

// Drag handlers
const onDragStart = (e, card, columnId) => {
    draggedCard.value = card
    draggedFromColumn.value = columnId
    e.dataTransfer.effectAllowed = 'move'
    e.dataTransfer.setData('text/plain', JSON.stringify({ cardId: card.id, columnId }))
    // Add drag styling after a short delay
    setTimeout(() => {
        e.target.classList.add('opacity-50')
    }, 0)
}

const onDragEnd = (e) => {
    e.target.classList.remove('opacity-50')
    draggedCard.value = null
    draggedFromColumn.value = null
    dropTargetColumn.value = null
    dropTargetIndex.value = null
}

const onDragOver = (e, columnId, index = null) => {
    e.preventDefault()
    e.dataTransfer.dropEffect = 'move'
    dropTargetColumn.value = columnId
    dropTargetIndex.value = index
}

const onDragLeave = (e) => {
    // Only clear if leaving the column entirely
    if (!e.currentTarget.contains(e.relatedTarget)) {
        dropTargetColumn.value = null
        dropTargetIndex.value = null
    }
}

const onDrop = (e, columnId, index = null) => {
    e.preventDefault()

    if (!draggedCard.value) return

    const fromColumn = draggedFromColumn.value
    const toColumn = columnId
    const card = draggedCard.value

    // Don't emit if dropped in same position
    if (fromColumn === toColumn && index === null) {
        return
    }

    emit('card-move', {
        card,
        fromColumn,
        toColumn,
        toIndex: index
    })

    // Reset drag state
    draggedCard.value = null
    draggedFromColumn.value = null
    dropTargetColumn.value = null
    dropTargetIndex.value = null
}

const onCardClick = (card, column) => {
    if (props.cardClickable) {
        emit('card-click', { card, column })
    }
}

const onAddCard = (column) => {
    emit('card-add', column)
}

const onAddColumn = () => {
    emit('column-add')
}

const onCardMenu = (e, card, column) => {
    e.stopPropagation()
    emit('card-menu', { card, column, event: e })
}

const onColumnMenu = (e, column) => {
    emit('column-menu', { column, event: e })
}

const getLabelColor = (index) => labelColors[index % labelColors.length]
</script>

<template>
    <div class="flex gap-4 overflow-x-auto pb-4">
        <!-- Columns -->
        <div
            v-for="column in columns"
            :key="column.id"
            class="flex-shrink-0 w-72 sm:w-80"
        >
            <!-- Column container -->
            <div
                :class="[
                    'bg-gray-100 dark:bg-gray-800/50 rounded-xl',
                    dropTargetColumn === column.id ? 'ring-2 ring-primary-500' : ''
                ]"
                @dragover="(e) => onDragOver(e, column.id)"
                @dragleave="onDragLeave"
                @drop="(e) => onDrop(e, column.id)"
            >
                <!-- Column header -->
                <div class="flex items-center justify-between px-3 py-3">
                    <div class="flex items-center gap-2">
                        <span
                            v-if="column.color"
                            class="w-2 h-2 rounded-full"
                            :style="{ backgroundColor: column.color }"
                        />
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                            {{ column.title }}
                        </h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-1.5 py-0.5 rounded">
                            {{ column.items?.length || 0 }}
                        </span>
                    </div>
                    <button
                        type="button"
                        class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                        @click="(e) => onColumnMenu(e, column)"
                    >
                        <EllipsisHorizontalIcon class="h-4 w-4" />
                    </button>
                </div>

                <!-- Cards container -->
                <div class="px-2 pb-2 space-y-2 min-h-[100px]">
                    <!-- Cards -->
                    <div
                        v-for="(card, index) in column.items"
                        :key="card.id"
                        draggable="true"
                        :class="[
                            'bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 cursor-grab active:cursor-grabbing transition-all',
                            cardClickable ? 'hover:border-primary-300 dark:hover:border-primary-600' : '',
                            draggedCard?.id === card.id ? 'opacity-50' : ''
                        ]"
                        @dragstart="(e) => onDragStart(e, card, column.id)"
                        @dragend="onDragEnd"
                        @dragover.stop="(e) => onDragOver(e, column.id, index)"
                        @drop.stop="(e) => onDrop(e, column.id, index)"
                        @click="() => onCardClick(card, column)"
                    >
                        <!-- Labels -->
                        <div v-if="card.labels?.length" class="flex flex-wrap gap-1 mb-2">
                            <span
                                v-for="(label, labelIndex) in card.labels"
                                :key="labelIndex"
                                :class="[
                                    'h-1.5 w-8 rounded-full',
                                    typeof label === 'string' ? getLabelColor(labelIndex) : ''
                                ]"
                                :style="typeof label === 'object' && label.color ? { backgroundColor: label.color } : {}"
                                :title="typeof label === 'object' ? label.name : label"
                            />
                        </div>

                        <!-- Title -->
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white">
                                {{ card.title }}
                            </h4>
                            <button
                                type="button"
                                class="flex-shrink-0 p-0.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded opacity-0 group-hover:opacity-100"
                                @click="(e) => onCardMenu(e, card, column)"
                            >
                                <EllipsisHorizontalIcon class="h-4 w-4" />
                            </button>
                        </div>

                        <!-- Description -->
                        <p v-if="card.description" class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                            {{ card.description }}
                        </p>

                        <!-- Footer -->
                        <div v-if="card.assignees?.length || card.dueDate || card.priority" class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <!-- Assignees -->
                            <div v-if="card.assignees?.length" class="flex -space-x-1.5">
                                <div
                                    v-for="(assignee, i) in card.assignees.slice(0, 3)"
                                    :key="i"
                                    class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300"
                                    :style="assignee.avatar ? { backgroundImage: `url(${assignee.avatar})`, backgroundSize: 'cover' } : {}"
                                    :title="assignee.name"
                                >
                                    <template v-if="!assignee.avatar">
                                        {{ assignee.name?.charAt(0).toUpperCase() }}
                                    </template>
                                </div>
                                <div
                                    v-if="card.assignees.length > 3"
                                    class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300"
                                >
                                    +{{ card.assignees.length - 3 }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Due date -->
                                <span
                                    v-if="card.dueDate"
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ card.dueDate }}
                                </span>

                                <!-- Priority -->
                                <span
                                    v-if="card.priority"
                                    :class="[
                                        'text-xs px-1.5 py-0.5 rounded font-medium',
                                        priorityColors[card.priority] || priorityColors.low
                                    ]"
                                >
                                    {{ card.priority }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Drop placeholder -->
                    <div
                        v-if="dropTargetColumn === column.id && draggedCard"
                        class="h-20 border-2 border-dashed border-primary-300 dark:border-primary-600 rounded-lg bg-primary-50/50 dark:bg-primary-900/10"
                    />
                </div>

                <!-- Add card button -->
                <button
                    v-if="allowAdd"
                    type="button"
                    class="w-full flex items-center justify-center gap-1 px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-200/50 dark:hover:bg-gray-700/50 rounded-b-xl transition-colors"
                    @click="() => onAddCard(column)"
                >
                    <PlusIcon class="h-4 w-4" />
                    Ajouter une carte
                </button>
            </div>
        </div>

        <!-- Add column button -->
        <div
            v-if="allowAddColumn"
            class="flex-shrink-0 w-72 sm:w-80"
        >
            <button
                type="button"
                class="w-full h-full min-h-[200px] flex flex-col items-center justify-center gap-2 bg-gray-100/50 dark:bg-gray-800/30 hover:bg-gray-200/50 dark:hover:bg-gray-700/30 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                @click="onAddColumn"
            >
                <PlusIcon class="h-6 w-6" />
                <span class="text-sm font-medium">Ajouter une colonne</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
