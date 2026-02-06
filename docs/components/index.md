# Composants Vue

CamboAdmin fournit une bibliotheque complete de **134+ composants Vue 3** prets a l'emploi.

## Categories

### UI de Base
- [Button](/components/button) - Boutons avec variantes
- [Badge](/components/badge) - Badges et labels
- [Avatar](/components/avatar) - Avatars utilisateurs
- [Spinner](/components/spinner) - Indicateurs de chargement
- [Skeleton](/components/skeleton) - Placeholders de chargement
- [Tooltip](/components/tooltip) - Infobulles
- [Divider](/components/divider) - Separateurs

### Formulaires
- [Form](/components/form) - Conteneur de formulaire
- [Input](/components/input) - Champs de texte
- [Textarea](/components/textarea) - Zones de texte
- [Select](/components/select) - Listes deroulantes
- [Checkbox](/components/checkbox) - Cases a cocher
- [Radio](/components/radio) - Boutons radio
- [Switch](/components/switch) - Interrupteurs
- [FileInput](/components/file-input) - Upload de fichiers

### Navigation
- [Tabs](/components/tabs) - Onglets
- [Breadcrumb](/components/breadcrumb) - Fil d'Ariane
- [Pagination](/components/pagination) - Pagination
- [Dropdown](/components/dropdown) - Menus deroulants

### Feedback
- [Alert](/components/alert) - Alertes
- [Toast](/components/toast) - Notifications toast
- [Modal](/components/modal) - Modales
- [Drawer](/components/drawer) - Tiroirs lateraux
- [Progress](/components/progress) - Barres de progression

### Data Display
- [Table](/components/table) - Tableaux de donnees
- [Card](/components/card) - Cartes
- [Accordion](/components/accordion) - Accordeons
- [Stats](/components/stats) - Statistiques

## Installation

Les composants sont automatiquement disponibles apres l'installation de CamboAdmin. Importez-les directement :

```vue
<script setup>
import Button from '@/Components/UI/Button.vue'
import Input from '@/Components/Form/Input.vue'
import DataTable from '@/Components/DataTable/DataTable.vue'
</script>
```

## Conventions

### Nommage

- Les composants suivent la convention PascalCase
- Les props utilisent kebab-case dans les templates
- Les events utilisent kebab-case

```vue
<template>
    <Button
        variant="primary"
        icon-left="plus"
        @click="handleClick"
    >
        Ajouter
    </Button>
</template>
```

### Slots

La plupart des composants supportent des slots pour la personnalisation :

```vue
<Card>
    <template #header>
        <h3>Titre</h3>
    </template>

    <template #default>
        Contenu principal
    </template>

    <template #footer>
        <Button>Action</Button>
    </template>
</Card>
```

### Icones

Les icones utilisent [Heroicons](https://heroicons.com/) :

```vue
<Icon name="user" class="w-5 h-5" />
<Button icon-left="plus">Ajouter</Button>
```

## Theming

Les composants utilisent des variables CSS pour le theming :

```css
:root {
    --color-primary: #3B82F6;
    --color-secondary: #6B7280;
    --color-success: #10B981;
    --color-danger: #EF4444;
    --color-warning: #F59E0B;
    --color-info: #3B82F6;
}
```

Modifiez ces variables pour personnaliser l'apparence de tous les composants.

## TypeScript

Tous les composants sont compatibles TypeScript avec des types exportes :

```typescript
import type { ButtonProps } from '@/Components/UI/Button.vue'
import type { InputProps } from '@/Components/Form/Input.vue'
```
