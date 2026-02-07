# cambo:component Command

The `cambo:component` command generates Vue 3 components with proper structure, props, emits, and slots.

## Basic Usage

```bash
php artisan cambo:component StatusBadge
```

This creates a component at `resources/js/Components/StatusBadge.vue`.

## Command Signature

```bash
php artisan cambo:component
    {name : The name of the component}
    {--category= : Category folder (UI, Form, Data, etc.)}
    {--with-props : Include example props}
    {--with-emits : Include example emits}
    {--with-slots : Include example slots}
    {--force : Overwrite existing file}
```

## Options

### `name` (Required)

The component name in PascalCase. The command will convert other formats automatically.

```bash
# All create StatusBadge.vue
php artisan cambo:component StatusBadge
php artisan cambo:component statusBadge
php artisan cambo:component status-badge
php artisan cambo:component status_badge
```

### `--category`

Place the component in a category folder. Common categories include:
- `UI` - Buttons, badges, alerts, etc.
- `Form` - Form inputs and controls
- `Data` - Tables, lists, charts
- `Layout` - Layout components
- `Containers` - Cards, panels, modals
- `Overlays` - Modals, drawers, tooltips
- `Navigation` - Menus, tabs, breadcrumbs
- `Feedback` - Loaders, progress, notifications

```bash
php artisan cambo:component StatusBadge --category=UI
# Creates: resources/js/Components/UI/StatusBadge.vue

php artisan cambo:component SearchInput --category=Form
# Creates: resources/js/Components/Form/SearchInput.vue
```

### `--with-props`

Include example props with documentation and validation.

```bash
php artisan cambo:component Button --with-props
```

### `--with-emits`

Include example event emitters with handler functions.

```bash
php artisan cambo:component Button --with-emits
```

### `--with-slots`

Include example slot patterns including default, named, and scoped slots.

```bash
php artisan cambo:component Card --with-slots
```

### `--force`

Overwrite an existing component file.

```bash
php artisan cambo:component Button --force
```

## Examples

### Basic Component

```bash
php artisan cambo:component Spinner
```

**Generated:** `resources/js/Components/Spinner.vue`

```vue
<script setup>
/**
 * Spinner Component
 *
 * @description A reusable component
 */

</script>

<template>
    <div class="relative">

        <slot />
    </div>
</template>
```

### Component with Props

```bash
php artisan cambo:component Button --category=UI --with-props
```

**Generated:** `resources/js/Components/UI/Button.vue`

```vue
<script setup>
/**
 * Button Component
 *
 * @description A reusable component
 */

const props = defineProps({
    /**
     * The variant style of the component
     */
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'primary', 'secondary'].includes(value),
    },
    /**
     * Whether the component is disabled
     */
    disabled: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <div class="relative">

        <slot />
    </div>
</template>
```

### Component with Emits

```bash
php artisan cambo:component Toggle --category=Form --with-props --with-emits
```

**Generated:** `resources/js/Components/Form/Toggle.vue`

```vue
<script setup>
/**
 * Toggle Component
 *
 * @description A reusable component
 */

const props = defineProps({
    /**
     * The variant style of the component
     */
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'primary', 'secondary'].includes(value),
    },
    /**
     * Whether the component is disabled
     */
    disabled: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['click', 'change', 'update:modelValue'])

const handleClick = (event) => {
    if (!props.disabled) {
        emit('click', event)
    }
}
</script>

<template>
    <div class="relative">

        <slot />
    </div>
</template>
```

### Component with Slots

```bash
php artisan cambo:component Card --category=Containers --with-slots
```

**Generated:** `resources/js/Components/Containers/Card.vue`

```vue
<script setup>
/**
 * Card Component
 *
 * @description A reusable component
 */

</script>

<template>
    <div class="relative">

        <!-- Default slot -->
        <slot />

        <!-- Named slot example -->
        <template v-if="$slots.header">
            <div class="header">
                <slot name="header" />
            </div>
        </template>

        <!-- Scoped slot example -->
        <slot name="item" :data="{ example: 'data' }" />
    </div>
</template>
```

### Full-Featured Component

```bash
php artisan cambo:component DataCard --category=Data --with-props --with-emits --with-slots
```

## Component Patterns

### v-model Support

Add v-model support to form components:

```vue
<script setup>
const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: '',
    },
})

const emit = defineEmits(['update:modelValue'])

const updateValue = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<template>
    <input
        :value="modelValue"
        @input="updateValue"
        class="..."
    />
</template>
```

**Usage:**

```vue
<CustomInput v-model="formData.name" />
```

### Multiple v-model Bindings

```vue
<script setup>
const props = defineProps({
    modelValue: String,
    selected: Array,
})

const emit = defineEmits(['update:modelValue', 'update:selected'])
</script>

<template>
    <div>
        <input :value="modelValue" @input="emit('update:modelValue', $event.target.value)" />
        <!-- selection logic -->
    </div>
</template>
```

**Usage:**

```vue
<MultiSelect v-model="search" v-model:selected="selectedItems" />
```

### Expose Methods

Expose methods for parent component access:

```vue
<script setup>
import { ref } from 'vue'

const inputRef = ref(null)

const focus = () => {
    inputRef.value?.focus()
}

const clear = () => {
    emit('update:modelValue', '')
}

defineExpose({
    focus,
    clear,
})
</script>
```

**Usage:**

```vue
<script setup>
import { ref } from 'vue'

const searchInput = ref(null)

const handleSearch = () => {
    searchInput.value.focus()
}
</script>

<template>
    <SearchInput ref="searchInput" />
</template>
```

### Provide/Inject Pattern

For component groups (like Accordion):

**Parent Component:**

```vue
<script setup>
import { provide, ref } from 'vue'

const activeItem = ref(null)

provide('accordion', {
    activeItem,
    setActiveItem: (id) => {
        activeItem.value = activeItem.value === id ? null : id
    },
})
</script>

<template>
    <div class="accordion">
        <slot />
    </div>
</template>
```

**Child Component:**

```vue
<script setup>
import { inject, computed } from 'vue'

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
})

const accordion = inject('accordion')
const isActive = computed(() => accordion.activeItem.value === props.id)

const toggle = () => {
    accordion.setActiveItem(props.id)
}
</script>

<template>
    <div class="accordion-item">
        <button @click="toggle">
            <slot name="header" />
        </button>
        <div v-show="isActive">
            <slot />
        </div>
    </div>
</template>
```

## Component Organization

### Recommended Directory Structure

```
resources/js/Components/
├── Containers/
│   ├── Card.vue
│   ├── Panel.vue
│   └── Section.vue
├── Data/
│   ├── Table.vue
│   ├── TableHead.vue
│   ├── TableBody.vue
│   ├── TableRow.vue
│   ├── TableCell.vue
│   ├── Pagination.vue
│   └── DescriptionList.vue
├── Feedback/
│   ├── Alert.vue
│   ├── Progress.vue
│   └── Spinner.vue
├── Form/
│   ├── Form.vue
│   ├── FormGroup.vue
│   ├── Input.vue
│   ├── Textarea.vue
│   ├── Select.vue
│   ├── Checkbox.vue
│   ├── Radio.vue
│   ├── Toggle.vue
│   └── SearchInput.vue
├── Layout/
│   ├── AdminLayout.vue
│   ├── PageHeader.vue
│   ├── Sidebar.vue
│   └── Navbar.vue
├── Navigation/
│   ├── Tabs.vue
│   ├── Breadcrumbs.vue
│   └── Menu.vue
├── Overlays/
│   ├── Modal.vue
│   ├── Drawer.vue
│   ├── ConfirmModal.vue
│   └── Tooltip.vue
└── UI/
    ├── Button.vue
    ├── Badge.vue
    ├── Avatar.vue
    └── Icon.vue
```

### Naming Conventions

| Pattern | Example |
|---------|---------|
| PascalCase | `StatusBadge.vue`, `DataTable.vue` |
| Prefixed for related | `Table.vue`, `TableHead.vue`, `TableRow.vue` |
| Action verbs for interactive | `ConfirmModal.vue`, `SearchInput.vue` |

## Best Practices

### 1. Single Responsibility

Each component should do one thing well:

```vue
<!-- Good: Single purpose -->
<SearchInput v-model="search" @search="handleSearch" />

<!-- Avoid: Too many responsibilities -->
<SuperInput
    v-model="value"
    :search="true"
    :filter="true"
    :sort="true"
    :export="true"
/>
```

### 2. Prop Validation

Always validate props:

```vue
<script setup>
const props = defineProps({
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value),
    },
    variant: {
        type: String,
        required: true,
        validator: (value) => ['primary', 'secondary', 'danger'].includes(value),
    },
})
</script>
```

### 3. Default Slot Content

Provide fallback content for slots:

```vue
<template>
    <div class="card">
        <slot name="header">
            <h3>Default Title</h3>
        </slot>
        <slot>
            <p>No content provided</p>
        </slot>
    </div>
</template>
```

### 4. Computed Classes

Use computed properties for dynamic classes:

```vue
<script setup>
import { computed } from 'vue'

const props = defineProps({
    variant: String,
    size: String,
})

const classes = computed(() => [
    'base-class',
    {
        'variant-primary': props.variant === 'primary',
        'variant-secondary': props.variant === 'secondary',
        'size-sm': props.size === 'sm',
        'size-lg': props.size === 'lg',
    },
])
</script>

<template>
    <button :class="classes">
        <slot />
    </button>
</template>
```

### 5. TypeScript Support (Optional)

For TypeScript projects:

```vue
<script setup lang="ts">
interface Props {
    variant?: 'primary' | 'secondary' | 'danger'
    size?: 'sm' | 'md' | 'lg'
    disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    size: 'md',
    disabled: false,
})

const emit = defineEmits<{
    click: [event: MouseEvent]
    change: [value: string]
}>()
</script>
```

## See Also

- [cambo:page](./page.md) - Generate pages
- [cambo:crud](./crud.md) - Generate complete CRUD modules
- [Component API](/components/) - Full component documentation
