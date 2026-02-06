# Button

Le composant Button est un bouton versatile avec de nombreuses variantes et options.

## Import

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
</script>
```

## Utilisation basique

```vue
<template>
    <Button>Cliquez-moi</Button>
</template>
```

## Variantes

```vue
<template>
    <Button variant="primary">Primary</Button>
    <Button variant="secondary">Secondary</Button>
    <Button variant="success">Success</Button>
    <Button variant="danger">Danger</Button>
    <Button variant="warning">Warning</Button>
    <Button variant="info">Info</Button>
    <Button variant="ghost">Ghost</Button>
    <Button variant="link">Link</Button>
</template>
```

## Tailles

```vue
<template>
    <Button size="xs">Extra Small</Button>
    <Button size="sm">Small</Button>
    <Button size="md">Medium</Button>
    <Button size="lg">Large</Button>
</template>
```

## Etats

### Outline

```vue
<Button variant="primary" outline>Outline</Button>
```

### Pill (arrondis)

```vue
<Button variant="primary" pill>Pill Button</Button>
```

### Loading

```vue
<Button variant="primary" loading>Chargement...</Button>
```

### Disabled

```vue
<Button variant="primary" disabled>Desactive</Button>
```

## Avec icones

```vue
<template>
    <!-- Icone a gauche -->
    <Button variant="primary" icon-left="plus">
        Ajouter
    </Button>

    <!-- Icone a droite -->
    <Button variant="primary" icon-right="arrow-right">
        Suivant
    </Button>

    <!-- Les deux -->
    <Button variant="primary" icon-left="download" icon-right="check">
        Telecharger
    </Button>
</template>
```

## Bouton icone

```vue
<script setup>
import IconButton from '@/Components/UI/IconButton.vue'
</script>

<template>
    <IconButton icon="pencil" aria-label="Modifier" />
    <IconButton icon="trash" variant="danger" aria-label="Supprimer" />
    <IconButton icon="eye" variant="ghost" aria-label="Voir" />
</template>
```

## Groupe de boutons

```vue
<script setup>
import ButtonGroup from '@/Components/UI/ButtonGroup.vue'
import Button from '@/Components/UI/Button.vue'
</script>

<template>
    <ButtonGroup>
        <Button variant="primary">Gauche</Button>
        <Button variant="primary">Centre</Button>
        <Button variant="primary">Droite</Button>
    </ButtonGroup>

    <!-- Vertical -->
    <ButtonGroup vertical>
        <Button variant="primary">Haut</Button>
        <Button variant="primary">Milieu</Button>
        <Button variant="primary">Bas</Button>
    </ButtonGroup>
</template>
```

## Comme lien

```vue
<template>
    <!-- Lien externe -->
    <Button href="https://example.com" target="_blank">
        Lien externe
    </Button>

    <!-- Lien Inertia -->
    <Button :href="route('users.index')">
        Utilisateurs
    </Button>
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `string` | `'primary'` | Variante visuelle |
| `size` | `string` | `'md'` | Taille du bouton |
| `outline` | `boolean` | `false` | Style outline |
| `pill` | `boolean` | `false` | Coins arrondis |
| `loading` | `boolean` | `false` | Etat de chargement |
| `disabled` | `boolean` | `false` | Desactive le bouton |
| `type` | `string` | `'button'` | Type HTML |
| `href` | `string` | `null` | URL pour lien |
| `icon-left` | `string` | `null` | Icone a gauche |
| `icon-right` | `string` | `null` | Icone a droite |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `click` | `MouseEvent` | Clic sur le bouton |

## Slots

| Slot | Description |
|------|-------------|
| `default` | Contenu du bouton |

## Exemples complets

### Formulaire

```vue
<template>
    <form @submit.prevent="submit">
        <div class="flex gap-2">
            <Button type="button" variant="ghost" @click="cancel">
                Annuler
            </Button>
            <Button type="submit" variant="primary" :loading="form.processing">
                Sauvegarder
            </Button>
        </div>
    </form>
</template>
```

### Actions de table

```vue
<template>
    <div class="flex gap-1">
        <IconButton
            icon="eye"
            variant="ghost"
            size="sm"
            aria-label="Voir"
            @click="view(item)"
        />
        <IconButton
            icon="pencil"
            variant="ghost"
            size="sm"
            aria-label="Modifier"
            @click="edit(item)"
        />
        <IconButton
            icon="trash"
            variant="ghost"
            size="sm"
            aria-label="Supprimer"
            @click="confirmDelete(item)"
        />
    </div>
</template>
```
