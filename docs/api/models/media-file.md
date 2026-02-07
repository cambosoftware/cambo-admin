# MediaFile Model

The `MediaFile` model manages uploaded files and media assets in CamboAdmin.

## Description

The MediaFile model provides a complete media management system with support for multiple file types, folder organization, automatic type detection, and file storage operations. It uses UUIDs and includes soft deletes.

## Usage

```php
use CamboSoftware\CamboAdmin\Models\MediaFile;

// Create from upload
$media = MediaFile::fromUpload(
    $request->file('image'),
    auth()->id(),
    $folderId
);
```

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | `uuid` | Unique identifier (UUID) |
| `user_id` | `int\|null` | Uploader user ID |
| `folder_id` | `int\|null` | Parent folder ID |
| `name` | `string` | Display name |
| `original_name` | `string` | Original filename |
| `disk` | `string` | Storage disk name |
| `path` | `string` | File path on disk |
| `mime_type` | `string` | MIME type |
| `type` | `string` | File type (image, video, document, etc.) |
| `extension` | `string` | File extension |
| `size` | `integer` | File size in bytes |
| `metadata` | `array` | Additional metadata (dimensions, etc.) |
| `alt_text` | `string\|null` | Image alt text |
| `description` | `string\|null` | File description |
| `is_public` | `boolean` | Whether publicly accessible |

## Fillable Attributes

```php
protected $fillable = [
    'user_id',
    'folder_id',
    'name',
    'original_name',
    'disk',
    'path',
    'mime_type',
    'type',
    'extension',
    'size',
    'metadata',
    'alt_text',
    'description',
    'is_public',
];
```

## Casts

```php
protected $casts = [
    'metadata' => 'array',
    'is_public' => 'boolean',
    'size' => 'integer',
];
```

## Appended Attributes

```php
protected $appends = ['url', 'thumbnail_url', 'human_size'];
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `user()` | none | `BelongsTo` | Get uploader user |
| `folder()` | none | `BelongsTo` | Get parent folder |
| `getUrlAttribute()` | none | `string` | Get file URL |
| `getThumbnailUrlAttribute()` | none | `?string` | Get thumbnail URL |
| `getHumanSizeAttribute()` | none | `string` | Get human-readable size |
| `determineType()` | `string $mimeType` (static) | `string` | Determine file type |
| `fromUpload()` | `UploadedFile, ?int $userId, ?int $folderId, string $disk` (static) | `self` | Create from upload |
| `deleteFromStorage()` | none | `bool` | Delete file from storage |
| `moveToFolder()` | `?int $folderId` | `self` | Move to folder |
| `scopeOfType()` | `string $type` | `Builder` | Filter by type |
| `scopeInFolder()` | `?int $folderId` | `Builder` | Filter by folder |
| `scopeSearch()` | `?string $search` | `Builder` | Search by name |

## Accessors

### getUrlAttribute()

Returns the full URL to the file.

```php
$url = $media->url;
// Returns: 'https://example.com/storage/media/2024/01/image-abc123.jpg'
```

### getThumbnailUrlAttribute()

Returns the thumbnail URL for images, null for other types.

```php
$thumbnail = $media->thumbnail_url;
// Returns URL or null if not an image
```

### getHumanSizeAttribute()

Returns human-readable file size.

```php
$size = $media->human_size;
// Returns: '2.5 Mo'
```

## Static Methods

### determineType()

```php
public static function determineType(string $mimeType): string
```

Determines file type from MIME type.

**Returns:**
- `'image'` - For image/* MIME types
- `'video'` - For video/* MIME types
- `'audio'` - For audio/* MIME types
- `'document'` - For PDFs, Word, Excel, PowerPoint, text files
- `'archive'` - For ZIP, RAR, 7z, tar, gzip
- `'other'` - For everything else

**Example:**

```php
$type = MediaFile::determineType('image/jpeg'); // 'image'
$type = MediaFile::determineType('application/pdf'); // 'document'
$type = MediaFile::determineType('application/zip'); // 'archive'
```

### fromUpload()

```php
public static function fromUpload(
    \Illuminate\Http\UploadedFile $file,
    ?int $userId = null,
    ?int $folderId = null,
    string $disk = 'public'
): self
```

Creates a MediaFile from an uploaded file.

**Parameters:**
- `$file` - The uploaded file
- `$userId` - Uploader user ID (optional)
- `$folderId` - Parent folder ID (optional)
- `$disk` - Storage disk (default: 'public')

**Example:**

```php
$media = MediaFile::fromUpload(
    $request->file('document'),
    auth()->id(),
    $request->folder_id,
    'public'
);
```

**Process:**
1. Gets original name, extension, MIME type, size
2. Determines file type
3. Generates unique filename with slug and random string
4. Stores file in `media/YYYY/MM/` directory
5. Extracts image dimensions if applicable
6. Creates MediaFile record

## Instance Methods

### deleteFromStorage()

```php
public function deleteFromStorage(): bool
```

Deletes the file from storage.

**Example:**

```php
$media->deleteFromStorage();
$media->delete(); // Also delete the record
```

### moveToFolder()

```php
public function moveToFolder(?int $folderId): self
```

Moves the file to a different folder.

**Parameters:**
- `$folderId` - Target folder ID (null for root)

**Example:**

```php
$media->moveToFolder($newFolderId);
```

## Scope Methods

### scopeOfType()

```php
public function scopeOfType($query, string $type)
```

Filter by file type.

**Example:**

```php
$images = MediaFile::ofType('image')->get();
$documents = MediaFile::ofType('document')->get();
```

### scopeInFolder()

```php
public function scopeInFolder($query, ?int $folderId)
```

Filter by folder.

**Example:**

```php
$rootFiles = MediaFile::inFolder(null)->get();
$folderFiles = MediaFile::inFolder(5)->get();
```

### scopeSearch()

```php
public function scopeSearch($query, ?string $search)
```

Search by name or original name.

**Example:**

```php
$results = MediaFile::search('report')->get();
```

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Models\MediaFile;
use CamboSoftware\CamboAdmin\Models\MediaFolder;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $files = MediaFile::query()
            ->when($request->folder_id, fn($q) => $q->inFolder($request->folder_id))
            ->when($request->type, fn($q) => $q->ofType($request->type))
            ->when($request->search, fn($q) => $q->search($request->search))
            ->with('folder')
            ->latest()
            ->paginate(24);

        return response()->json($files);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        $media = MediaFile::fromUpload(
            $request->file('file'),
            auth()->id(),
            $request->folder_id
        );

        return response()->json($media, 201);
    }

    public function update(Request $request, MediaFile $media)
    {
        $media->update($request->only([
            'name',
            'alt_text',
            'description',
        ]));

        return response()->json($media);
    }

    public function move(Request $request, MediaFile $media)
    {
        $media->moveToFolder($request->folder_id);

        return response()->json($media);
    }

    public function destroy(MediaFile $media)
    {
        $media->deleteFromStorage();
        $media->delete();

        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $files = MediaFile::whereIn('id', $request->ids)->get();

        foreach ($files as $file) {
            $file->deleteFromStorage();
            $file->delete();
        }

        return response()->json(['success' => true, 'deleted' => count($files)]);
    }
}
```

## File Types Reference

| Type | MIME Types |
|------|------------|
| `image` | image/* |
| `video` | video/* |
| `audio` | audio/* |
| `document` | application/pdf, application/msword, application/vnd.openxmlformats-*, text/plain, text/csv |
| `archive` | application/zip, application/x-rar-compressed, application/x-7z-compressed, application/x-tar, application/gzip |
| `other` | Everything else |

## Metadata Structure

For images, metadata includes dimensions:

```php
$media->metadata = [
    'width' => 1920,
    'height' => 1080,
];
```

## Storage Path Convention

Files are stored using the following path pattern:

```
media/{YYYY}/{MM}/{slug-randomstring}.{extension}
```

Example: `media/2024/01/profile-photo-a8f3k2x9.jpg`

## Database Schema

```php
Schema::create('media_files', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('folder_id')->nullable()->constrained('media_folders')->nullOnDelete();
    $table->string('name');
    $table->string('original_name');
    $table->string('disk')->default('public');
    $table->string('path');
    $table->string('mime_type');
    $table->string('type');
    $table->string('extension');
    $table->unsignedBigInteger('size');
    $table->json('metadata')->nullable();
    $table->string('alt_text')->nullable();
    $table->text('description')->nullable();
    $table->boolean('is_public')->default(true);
    $table->timestamps();
    $table->softDeletes();

    $table->index(['type', 'created_at']);
    $table->index('folder_id');
});
```

## Source Code

**Location:** `src/Models/MediaFile.php`

**Namespace:** `CamboSoftware\CamboAdmin\Models`

**Uses:** `HasUuids`, `SoftDeletes`
