# ContextMenu

A menu that appears on right-click, providing context-specific actions.

## Import

```vue
<script setup>
import ContextMenu from '@/Components/Overlays/ContextMenu.vue'
import ContextMenuItem from '@/Components/Overlays/ContextMenuItem.vue'
import ContextMenuDivider from '@/Components/Overlays/ContextMenuDivider.vue'
</script>
```

## Basic Usage

```vue
<template>
  <ContextMenu>
    <template #trigger>
      <div class="p-8 bg-gray-100 rounded-lg">
        Right-click here
      </div>
    </template>

    <ContextMenuItem @click="cut">Cut</ContextMenuItem>
    <ContextMenuItem @click="copy">Copy</ContextMenuItem>
    <ContextMenuItem @click="paste">Paste</ContextMenuItem>
    <ContextMenuDivider />
    <ContextMenuItem @click="remove" variant="danger">Delete</ContextMenuItem>
  </ContextMenu>
</template>
```

## Props

### ContextMenu

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `disabled` | `boolean` | `false` | Disable context menu |

### ContextMenuItem

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `string` | `'default'` | Style: `default`, `danger` |
| `disabled` | `boolean` | `false` | Disable the item |
| `icon` | `Component` | `null` | Icon component |
| `shortcut` | `string` | `null` | Keyboard shortcut hint |

## Slots

| Slot | Description |
|------|-------------|
| `trigger` | Element that triggers context menu |
| `default` | Menu items |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `open` | `{ x, y }` | Menu opened at position |
| `close` | - | Menu closed |

## Examples

### File Manager Context Menu

```vue
<script setup>
import {
  FolderIcon,
  DocumentIcon,
  ArrowDownTrayIcon,
  PencilIcon,
  TrashIcon,
  ShareIcon
} from '@heroicons/vue/24/outline'
</script>

<template>
  <ContextMenu v-for="file in files" :key="file.id">
    <template #trigger>
      <div class="p-4 border rounded-lg hover:border-indigo-500 cursor-pointer">
        <DocumentIcon class="w-12 h-12 text-gray-400 mx-auto" />
        <p class="text-sm text-center mt-2">{{ file.name }}</p>
      </div>
    </template>

    <ContextMenuItem :icon="FolderIcon" @click="openFile(file)">
      Open
    </ContextMenuItem>
    <ContextMenuItem :icon="ArrowDownTrayIcon" @click="download(file)">
      Download
    </ContextMenuItem>
    <ContextMenuItem :icon="ShareIcon" @click="share(file)">
      Share
    </ContextMenuItem>
    <ContextMenuDivider />
    <ContextMenuItem :icon="PencilIcon" @click="rename(file)">
      Rename
    </ContextMenuItem>
    <ContextMenuItem :icon="TrashIcon" variant="danger" @click="remove(file)">
      Delete
    </ContextMenuItem>
  </ContextMenu>
</template>
```

### Table Row Context Menu

```vue
<template>
  <table>
    <tbody>
      <ContextMenu v-for="row in data" :key="row.id">
        <template #trigger>
          <tr class="hover:bg-gray-50 cursor-context-menu">
            <td>{{ row.id }}</td>
            <td>{{ row.name }}</td>
            <td>{{ row.email }}</td>
          </tr>
        </template>

        <ContextMenuItem @click="view(row)" shortcut="Enter">
          View Details
        </ContextMenuItem>
        <ContextMenuItem @click="edit(row)" shortcut="E">
          Edit
        </ContextMenuItem>
        <ContextMenuItem @click="duplicate(row)" shortcut="Ctrl+D">
          Duplicate
        </ContextMenuItem>
        <ContextMenuDivider />
        <ContextMenuItem @click="remove(row)" variant="danger" shortcut="Del">
          Delete
        </ContextMenuItem>
      </ContextMenu>
    </tbody>
  </table>
</template>
```

### With Keyboard Shortcuts

```vue
<ContextMenu>
  <template #trigger>
    <div class="editor-area">
      <!-- Editor content -->
    </div>
  </template>

  <ContextMenuItem @click="undo" shortcut="Ctrl+Z">Undo</ContextMenuItem>
  <ContextMenuItem @click="redo" shortcut="Ctrl+Y">Redo</ContextMenuItem>
  <ContextMenuDivider />
  <ContextMenuItem @click="cut" shortcut="Ctrl+X">Cut</ContextMenuItem>
  <ContextMenuItem @click="copy" shortcut="Ctrl+C">Copy</ContextMenuItem>
  <ContextMenuItem @click="paste" shortcut="Ctrl+V">Paste</ContextMenuItem>
  <ContextMenuDivider />
  <ContextMenuItem @click="selectAll" shortcut="Ctrl+A">
    Select All
  </ContextMenuItem>
</ContextMenu>
```

### Nested Submenus

```vue
<ContextMenu>
  <template #trigger>
    <div>Right-click me</div>
  </template>

  <ContextMenuItem>New File</ContextMenuItem>
  <ContextMenuItem>New Folder</ContextMenuItem>
  <ContextMenuDivider />
  <ContextMenuItem>
    Sort By
    <template #submenu>
      <ContextMenuItem @click="sortBy('name')">Name</ContextMenuItem>
      <ContextMenuItem @click="sortBy('date')">Date</ContextMenuItem>
      <ContextMenuItem @click="sortBy('size')">Size</ContextMenuItem>
      <ContextMenuItem @click="sortBy('type')">Type</ContextMenuItem>
    </template>
  </ContextMenuItem>
  <ContextMenuItem>
    View
    <template #submenu>
      <ContextMenuItem @click="setView('grid')">Grid</ContextMenuItem>
      <ContextMenuItem @click="setView('list')">List</ContextMenuItem>
      <ContextMenuItem @click="setView('details')">Details</ContextMenuItem>
    </template>
  </ContextMenuItem>
</ContextMenu>
```

## Keyboard Navigation

- `Escape` - Close menu
- `Arrow Down` - Next item
- `Arrow Up` - Previous item
- `Enter` - Select item
- `Arrow Right` - Open submenu
- `Arrow Left` - Close submenu

## Accessibility

- Proper ARIA menu roles
- Keyboard navigation
- Focus management
- Screen reader support

## Playground

Try the ContextMenu component:

<LiveDemo>
  <div style="display: flex; gap: 24px; align-items: flex-start;">
    <div style="padding: 32px; background: #f3f4f6; border-radius: 8px; border: 2px dashed #d1d5db; text-align: center; color: #6b7280; font-size: 14px;">
      Right-click here
    </div>
    <div style="background: white; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; padding: 4px; min-width: 180px;">
      <div style="padding: 8px 12px; display: flex; align-items: center; gap: 12px; border-radius: 4px; cursor: pointer; font-size: 14px; color: #374151;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
        Open
      </div>
      <div style="padding: 8px 12px; display: flex; align-items: center; gap: 12px; border-radius: 4px; cursor: pointer; font-size: 14px; color: #374151; background: #f3f4f6;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Download
      </div>
      <div style="padding: 8px 12px; display: flex; align-items: center; gap: 12px; border-radius: 4px; cursor: pointer; font-size: 14px; color: #374151;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
        Share
      </div>
      <div style="height: 1px; background: #e5e7eb; margin: 4px 0;"></div>
      <div style="padding: 8px 12px; display: flex; align-items: center; gap: 12px; border-radius: 4px; cursor: pointer; font-size: 14px; color: #ef4444;">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        Delete
      </div>
    </div>
  </div>

  <template #code>

```vue
<template>
  <ContextMenu>
    <template #trigger>
      <div class="p-8 bg-gray-100 rounded-lg">
        Right-click here
      </div>
    </template>

    <ContextMenuItem :icon="FolderIcon" @click="open">Open</ContextMenuItem>
    <ContextMenuItem :icon="DownloadIcon" @click="download">Download</ContextMenuItem>
    <ContextMenuItem :icon="ShareIcon" @click="share">Share</ContextMenuItem>
    <ContextMenuDivider />
    <ContextMenuItem :icon="TrashIcon" variant="danger" @click="remove">Delete</ContextMenuItem>
  </ContextMenu>
</template>
```

  </template>
</LiveDemo>
