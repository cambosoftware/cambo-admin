# File Manager

CamboAdmin includes a complete file management system for uploading, organizing, and managing media files with folder support, file type detection, and integration with Laravel's filesystem.

## Introduction

The file manager module offers:

- File uploads with drag-and-drop support
- Folder organization with nesting
- File type detection (images, documents, videos, audio, archives)
- Image metadata (dimensions, size)
- File search and filtering
- Multiple storage disk support
- Soft delete with trash recovery
- File picker for forms

## Configuration

### Enable/Disable Module

```php
// config/cambo-admin.php
'modules' => [
    'media' => true,
],
```

### Media Settings

```php
// config/cambo-admin.php
'media' => [
    'disk' => 'public',           // Storage disk
    'max_upload_size' => 10240,   // KB (10MB)
    'allowed_types' => [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'],
        'video' => ['mp4', 'avi', 'mov', 'wmv'],
        'audio' => ['mp3', 'wav', 'ogg'],
        'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
    ],
],
```

### Storage Disk Configuration

```php
// config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],

    // Optional: Dedicated media disk
    'media' => [
        'driver' => 'local',
        'root' => storage_path('app/public/media'),
        'url' => env('APP_URL').'/storage/media',
        'visibility' => 'public',
    ],

    // Optional: S3 for production
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
    ],
],
```

Create the symbolic link:

```bash
php artisan storage:link
```

## Usage Examples

### Media File Model

```php
use CamboSoftware\CamboAdmin\Models\MediaFile;
```

#### MediaFile Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | uuid | Unique identifier |
| `user_id` | integer | Uploader user ID |
| `folder_id` | integer | Parent folder ID (nullable) |
| `name` | string | Display name |
| `original_name` | string | Original filename |
| `disk` | string | Storage disk |
| `path` | string | File path on disk |
| `mime_type` | string | MIME type |
| `type` | string | File type category |
| `extension` | string | File extension |
| `size` | integer | File size in bytes |
| `metadata` | array | Additional metadata (dimensions, etc.) |
| `alt_text` | string | Alt text for images |
| `description` | string | File description |
| `is_public` | boolean | Public accessibility |
| `created_at` | datetime | Upload timestamp |
| `deleted_at` | datetime | Soft delete timestamp |

### Uploading Files

#### From Uploaded File

```php
use CamboSoftware\CamboAdmin\Models\MediaFile;
use Illuminate\Http\Request;

public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:10240', // 10MB max
    ]);

    $mediaFile = MediaFile::fromUpload(
        file: $request->file('file'),
        userId: auth()->id(),
        folderId: $request->folder_id,
        disk: 'public'
    );

    return response()->json($mediaFile);
}
```

#### Multiple File Upload

```php
public function uploadMultiple(Request $request)
{
    $request->validate([
        'files.*' => 'required|file|max:10240',
    ]);

    $uploaded = [];

    foreach ($request->file('files') as $file) {
        $uploaded[] = MediaFile::fromUpload(
            file: $file,
            userId: auth()->id(),
            folderId: $request->folder_id
        );
    }

    return response()->json($uploaded);
}
```

### File Type Detection

```php
// Automatically determined from MIME type
$type = MediaFile::determineType('image/jpeg'); // 'image'
$type = MediaFile::determineType('application/pdf'); // 'document'
$type = MediaFile::determineType('video/mp4'); // 'video'
$type = MediaFile::determineType('audio/mpeg'); // 'audio'
$type = MediaFile::determineType('application/zip'); // 'archive'
$type = MediaFile::determineType('application/octet-stream'); // 'other'
```

### Accessing Files

#### Get File URL

```php
$file = MediaFile::find($id);

// Full URL
$url = $file->url; // https://example.com/storage/media/2024/01/image-abc123.jpg

// Thumbnail URL (for images)
$thumbnail = $file->thumbnail_url;
```

#### Get Human-Readable Size

```php
$file->human_size; // "1.5 MB"
```

#### Get Image Dimensions

```php
// Stored in metadata for images
$width = $file->metadata['width']; // 1920
$height = $file->metadata['height']; // 1080
```

### Querying Files

#### Filter by Type

```php
// Get all images
$images = MediaFile::ofType('image')->get();

// Get all documents
$documents = MediaFile::ofType('document')->get();
```

#### Filter by Folder

```php
// Get files in specific folder
$files = MediaFile::inFolder($folderId)->get();

// Get root files (no folder)
$rootFiles = MediaFile::inFolder(null)->get();
```

#### Search Files

```php
$files = MediaFile::search('logo')->get();
// Searches in name and original_name
```

#### Combined Query

```php
$files = MediaFile::query()
    ->ofType('image')
    ->inFolder($folderId)
    ->search($request->search)
    ->latest()
    ->paginate(24);
```

### Managing Files

#### Move to Folder

```php
$file = MediaFile::find($id);
$file->moveToFolder($newFolderId);
```

#### Update File Details

```php
$file->update([
    'name' => 'New Name',
    'alt_text' => 'Description of image',
    'description' => 'Detailed description',
    'is_public' => true,
]);
```

#### Delete File

```php
// Soft delete (moves to trash)
$file->delete();

// Permanently delete with file
$file->deleteFromStorage();
$file->forceDelete();
```

#### Restore from Trash

```php
$file = MediaFile::withTrashed()->find($id);
$file->restore();
```

### Media Folders

```php
use CamboSoftware\CamboAdmin\Models\MediaFolder;
```

#### MediaFolder Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `id` | integer | Unique identifier |
| `user_id` | integer | Owner user ID |
| `parent_id` | integer | Parent folder ID (nullable) |
| `name` | string | Folder name |
| `slug` | string | URL-friendly name |
| `color` | string | Folder color (hex) |

#### Create Folder

```php
$folder = MediaFolder::create([
    'user_id' => auth()->id(),
    'parent_id' => null, // Root folder
    'name' => 'My Documents',
    'color' => '#3B82F6',
]);
```

#### Nested Folders

```php
// Create subfolder
$subfolder = MediaFolder::create([
    'user_id' => auth()->id(),
    'parent_id' => $folder->id,
    'name' => 'Invoices',
]);

// Get parent
$parent = $subfolder->parent;

// Get children
$children = $folder->children;

// Get all descendants
$descendants = $folder->descendants;
```

#### Get Folder Path

```php
$path = $folder->path; // "My Documents/Invoices"
```

#### Get Breadcrumb

```php
$breadcrumb = $folder->getBreadcrumb();
// [
//     ['id' => 1, 'name' => 'My Documents'],
//     ['id' => 2, 'name' => 'Invoices'],
// ]
```

#### Count Files Recursively

```php
$count = $folder->files_count; // Includes files in subfolders
```

### File Manager Controller

```php
<?php

namespace App\Http\Controllers\Admin;

use CamboSoftware\CamboAdmin\Models\MediaFile;
use CamboSoftware\CamboAdmin\Models\MediaFolder;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $folderId = $request->folder_id;
        $folder = $folderId ? MediaFolder::find($folderId) : null;

        $folders = MediaFolder::where('parent_id', $folderId)
            ->orderBy('name')
            ->get();

        $files = MediaFile::inFolder($folderId)
            ->when($request->type, fn($q, $type) => $q->ofType($type))
            ->when($request->search, fn($q, $search) => $q->search($search))
            ->latest()
            ->paginate(24);

        return inertia('FileManager/Index', [
            'folder' => $folder,
            'breadcrumb' => $folder?->getBreadcrumb() ?? [],
            'folders' => $folders,
            'files' => $files,
            'filters' => $request->only(['type', 'search']),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:' . config('cambo-admin.media.max_upload_size'),
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            // Validate file type
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedTypes = config('cambo-admin.media.allowed_types');
            $allowed = collect($allowedTypes)->flatten()->toArray();

            if (!in_array($extension, $allowed)) {
                continue;
            }

            $uploaded[] = MediaFile::fromUpload(
                file: $file,
                userId: auth()->id(),
                folderId: $request->folder_id,
                disk: config('cambo-admin.media.disk')
            );
        }

        return response()->json($uploaded);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media_folders,id',
        ]);

        $folder = MediaFolder::create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'color' => $request->color ?? '#6B7280',
        ]);

        return response()->json($folder);
    }

    public function updateFile(Request $request, MediaFile $file)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file->update($request->only(['name', 'alt_text', 'description']));

        return response()->json($file);
    }

    public function moveFile(Request $request, MediaFile $file)
    {
        $request->validate([
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        $file->moveToFolder($request->folder_id);

        return response()->json($file);
    }

    public function deleteFile(MediaFile $file)
    {
        $file->delete();

        return response()->json(['success' => true]);
    }

    public function deleteFolder(MediaFolder $folder)
    {
        // Move files to parent folder
        MediaFile::where('folder_id', $folder->id)
            ->update(['folder_id' => $folder->parent_id]);

        // Move subfolders to parent
        MediaFolder::where('parent_id', $folder->id)
            ->update(['parent_id' => $folder->parent_id]);

        $folder->delete();

        return response()->json(['success' => true]);
    }

    public function trash()
    {
        $files = MediaFile::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest('deleted_at')
            ->paginate(24);

        return inertia('FileManager/Trash', [
            'files' => $files,
        ]);
    }

    public function restore(MediaFile $file)
    {
        $file = MediaFile::withTrashed()->findOrFail($file->id);
        $file->restore();

        return response()->json($file);
    }

    public function forceDelete(MediaFile $file)
    {
        $file = MediaFile::withTrashed()->findOrFail($file->id);
        $file->deleteFromStorage();
        $file->forceDelete();

        return response()->json(['success' => true]);
    }

    public function emptyTrash()
    {
        $files = MediaFile::onlyTrashed()
            ->where('user_id', auth()->id())
            ->get();

        foreach ($files as $file) {
            $file->deleteFromStorage();
            $file->forceDelete();
        }

        return response()->json(['success' => true]);
    }
}
```

## Available Options

### Supported File Types

| Category | Extensions | MIME Types |
|----------|------------|------------|
| Image | jpg, jpeg, png, gif, svg, webp | image/* |
| Document | pdf, doc, docx, xls, xlsx, ppt, pptx, txt, csv | application/pdf, application/msword, etc. |
| Video | mp4, avi, mov, wmv | video/* |
| Audio | mp3, wav, ogg | audio/* |
| Archive | zip, rar, 7z, tar, gz | application/zip, etc. |

### MediaFile Model Methods

| Method | Parameters | Returns | Description |
|--------|------------|---------|-------------|
| `fromUpload()` | `UploadedFile, userId, folderId, disk` | MediaFile | Create from upload |
| `determineType()` | `string $mimeType` | string | Get type from MIME |
| `deleteFromStorage()` | - | bool | Delete physical file |
| `moveToFolder()` | `?int $folderId` | self | Move to folder |

### MediaFile Query Scopes

| Scope | Parameters | Description |
|-------|------------|-------------|
| `ofType()` | `string $type` | Filter by type |
| `inFolder()` | `?int $folderId` | Filter by folder |
| `search()` | `?string $search` | Search by name |

### MediaFolder Model Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `path` | string | Full folder path |
| `getBreadcrumb()` | array | Breadcrumb trail |
| `files_count` | int | Recursive file count |
| `children` | Collection | Direct subfolders |
| `descendants` | Collection | All nested subfolders |

## Vue File Manager Component

```vue
<!-- resources/js/Pages/FileManager/Index.vue -->
<template>
  <div class="file-manager">
    <!-- Toolbar -->
    <div class="flex items-center justify-between mb-4 p-4 bg-white rounded-lg shadow">
      <div class="flex items-center gap-4">
        <button @click="createFolder" class="btn btn-secondary">
          <FolderPlusIcon class="w-5 h-5 mr-2" />
          New Folder
        </button>
        <button @click="$refs.fileInput.click()" class="btn btn-primary">
          <UploadIcon class="w-5 h-5 mr-2" />
          Upload Files
        </button>
        <input
          ref="fileInput"
          type="file"
          multiple
          class="hidden"
          @change="uploadFiles"
        />
      </div>

      <div class="flex items-center gap-4">
        <select v-model="filters.type" @change="applyFilters" class="form-select">
          <option value="">All Types</option>
          <option value="image">Images</option>
          <option value="document">Documents</option>
          <option value="video">Videos</option>
          <option value="audio">Audio</option>
        </select>
        <input
          v-model="filters.search"
          @input="debounceSearch"
          type="search"
          placeholder="Search files..."
          class="form-input"
        />
      </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 mb-4 text-sm">
      <button @click="navigateToFolder(null)" class="hover:text-primary">
        <HomeIcon class="w-4 h-4" />
      </button>
      <template v-for="(crumb, index) in breadcrumb" :key="crumb.id">
        <ChevronRightIcon class="w-4 h-4 text-gray-400" />
        <button
          @click="navigateToFolder(crumb.id)"
          class="hover:text-primary"
          :class="{ 'font-medium': index === breadcrumb.length - 1 }"
        >
          {{ crumb.name }}
        </button>
      </template>
    </nav>

    <!-- Drop Zone -->
    <div
      @dragover.prevent="dragging = true"
      @dragleave.prevent="dragging = false"
      @drop.prevent="handleDrop"
      class="relative"
      :class="{ 'ring-2 ring-primary ring-offset-2': dragging }"
    >
      <!-- Folders -->
      <div v-if="folders.length" class="grid grid-cols-6 gap-4 mb-6">
        <div
          v-for="folder in folders"
          :key="folder.id"
          @dblclick="navigateToFolder(folder.id)"
          class="p-4 bg-white rounded-lg shadow cursor-pointer hover:shadow-md transition"
        >
          <FolderIcon class="w-12 h-12 mx-auto mb-2" :style="{ color: folder.color }" />
          <p class="text-center text-sm font-medium truncate">{{ folder.name }}</p>
        </div>
      </div>

      <!-- Files Grid -->
      <div class="grid grid-cols-6 gap-4">
        <div
          v-for="file in files.data"
          :key="file.id"
          @click="selectFile(file)"
          @dblclick="openFile(file)"
          class="relative p-2 bg-white rounded-lg shadow cursor-pointer hover:shadow-md transition"
          :class="{ 'ring-2 ring-primary': selectedFile?.id === file.id }"
        >
          <!-- Image Preview -->
          <div v-if="file.type === 'image'" class="aspect-square mb-2">
            <img :src="file.thumbnail_url" :alt="file.alt_text" class="w-full h-full object-cover rounded" />
          </div>
          <!-- File Icon -->
          <div v-else class="aspect-square mb-2 flex items-center justify-center bg-gray-100 rounded">
            <component :is="getFileIcon(file.type)" class="w-12 h-12 text-gray-400" />
          </div>

          <p class="text-xs truncate">{{ file.name }}</p>
          <p class="text-xs text-gray-400">{{ file.human_size }}</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!folders.length && !files.data.length" class="text-center py-12">
        <FolderOpenIcon class="w-16 h-16 mx-auto text-gray-300 mb-4" />
        <p class="text-gray-500">This folder is empty</p>
        <p class="text-sm text-gray-400">Drag and drop files here or click Upload</p>
      </div>

      <!-- Pagination -->
      <Pagination v-if="files.last_page > 1" :links="files.links" class="mt-6" />
    </div>

    <!-- File Details Sidebar -->
    <Transition name="slide">
      <div v-if="selectedFile" class="fixed right-0 top-0 h-full w-80 bg-white shadow-lg p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-semibold">File Details</h3>
          <button @click="selectedFile = null" class="text-gray-400 hover:text-gray-600">
            <XIcon class="w-5 h-5" />
          </button>
        </div>

        <div v-if="selectedFile.type === 'image'" class="mb-4">
          <img :src="selectedFile.url" :alt="selectedFile.alt_text" class="w-full rounded" />
        </div>

        <dl class="space-y-2 text-sm">
          <div>
            <dt class="text-gray-500">Name</dt>
            <dd>{{ selectedFile.original_name }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Size</dt>
            <dd>{{ selectedFile.human_size }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Type</dt>
            <dd>{{ selectedFile.mime_type }}</dd>
          </div>
          <div v-if="selectedFile.metadata?.width">
            <dt class="text-gray-500">Dimensions</dt>
            <dd>{{ selectedFile.metadata.width }} x {{ selectedFile.metadata.height }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Uploaded</dt>
            <dd>{{ formatDate(selectedFile.created_at) }}</dd>
          </div>
        </dl>

        <div class="mt-6 space-y-2">
          <button @click="copyUrl(selectedFile)" class="btn btn-secondary w-full">
            Copy URL
          </button>
          <button @click="deleteFile(selectedFile)" class="btn btn-danger w-full">
            Delete
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
  folder: Object,
  breadcrumb: Array,
  folders: Array,
  files: Object,
  filters: Object,
});

const selectedFile = ref(null);
const dragging = ref(false);
const filters = reactive({ ...props.filters });

const navigateToFolder = (folderId) => {
  router.get('/admin/files', { folder_id: folderId });
};

const uploadFiles = async (event) => {
  const formData = new FormData();
  for (const file of event.target.files) {
    formData.append('files[]', file);
  }
  formData.append('folder_id', props.folder?.id || '');

  await fetch('/admin/files/upload', {
    method: 'POST',
    body: formData,
  });

  router.reload();
};

const handleDrop = async (event) => {
  dragging.value = false;
  const files = event.dataTransfer.files;

  const formData = new FormData();
  for (const file of files) {
    formData.append('files[]', file);
  }
  formData.append('folder_id', props.folder?.id || '');

  await fetch('/admin/files/upload', {
    method: 'POST',
    body: formData,
  });

  router.reload();
};

const selectFile = (file) => {
  selectedFile.value = file;
};

const deleteFile = async (file) => {
  if (!confirm('Are you sure you want to delete this file?')) return;

  await fetch(`/admin/files/${file.id}`, { method: 'DELETE' });
  selectedFile.value = null;
  router.reload();
};

const applyFilters = () => {
  router.get('/admin/files', {
    folder_id: props.folder?.id,
    ...filters,
  });
};

const debounceSearch = debounce(applyFilters, 300);
</script>
```

## Image Processing

For image manipulation (thumbnails, resizing), integrate with Intervention Image:

```bash
composer require intervention/image
```

```php
use Intervention\Image\Facades\Image;

// Generate thumbnail
public function generateThumbnail(MediaFile $file, int $width = 200, int $height = 200): string
{
    if ($file->type !== 'image') {
        throw new \InvalidArgumentException('File is not an image');
    }

    $image = Image::make(Storage::disk($file->disk)->get($file->path));
    $image->fit($width, $height);

    $thumbnailPath = str_replace(
        '.' . $file->extension,
        "_thumb.{$file->extension}",
        $file->path
    );

    Storage::disk($file->disk)->put($thumbnailPath, $image->encode());

    return $thumbnailPath;
}
```

## Best Practices

1. **Validate file types**: Always validate uploads against allowed types
2. **Limit file sizes**: Set reasonable size limits for your use case
3. **Use queues**: Process large uploads asynchronously
4. **Generate thumbnails**: Create thumbnails for images to improve performance
5. **Clean up**: Periodically remove orphaned files from storage
6. **Backup**: Regularly backup your media files
7. **CDN**: Consider using a CDN for static assets in production

```php
// Cleanup orphaned files command
public function handle()
{
    $storedPaths = MediaFile::pluck('path')->toArray();
    $diskFiles = Storage::disk('public')->allFiles('media');

    foreach ($diskFiles as $file) {
        if (!in_array($file, $storedPaths)) {
            Storage::disk('public')->delete($file);
            $this->info("Deleted orphaned file: {$file}");
        }
    }
}
```
