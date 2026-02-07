<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use App\Models\MediaFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MediaController extends Controller
{
    /**
     * Display the file manager.
     */
    public function index(Request $request)
    {
        $folderId = $request->get('folder');
        $type = $request->get('type');
        $search = $request->get('search');
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Get current folder
        $currentFolder = $folderId ? MediaFolder::find($folderId) : null;

        // Get folders
        $foldersQuery = MediaFolder::where('parent_id', $folderId)
            ->withCount('files')
            ->orderBy('name');

        // Get files
        $filesQuery = MediaFile::inFolder($folderId)
            ->search($search)
            ->when($type, fn($q) => $q->ofType($type))
            ->orderBy($sort, $direction);

        $folders = $foldersQuery->get();
        $files = $filesQuery->paginate(24)->withQueryString();

        // Get breadcrumb
        $breadcrumb = $currentFolder ? $currentFolder->getBreadcrumb() : [];

        // Get storage stats
        $storageStats = $this->getStorageStats();

        return Inertia::render('Media/Index', [
            'currentFolder' => $currentFolder,
            'folders' => $folders,
            'files' => $files,
            'breadcrumb' => $breadcrumb,
            'storageStats' => $storageStats,
            'filters' => [
                'folder' => $folderId,
                'type' => $type,
                'search' => $search,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    /**
     * Upload files.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:51200', // 50MB max
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $mediaFile = MediaFile::fromUpload(
                $file,
                $request->user()->id,
                $request->folder_id
            );
            $uploaded[] = $mediaFile;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'files' => $uploaded,
            ]);
        }

        return back()->with('success', count($uploaded) . ' fichier(s) uploadé(s).');
    }

    /**
     * Update a file.
     */
    public function update(Request $request, MediaFile $media)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        $media->update($request->only(['name', 'alt_text', 'description', 'folder_id']));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'file' => $media]);
        }

        return back()->with('success', 'Fichier mis à jour.');
    }

    /**
     * Delete a file.
     */
    public function destroy(Request $request, MediaFile $media)
    {
        $media->deleteFromStorage();
        $media->forceDelete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Fichier supprimé.');
    }

    /**
     * Bulk delete files.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_files,id',
        ]);

        $files = MediaFile::whereIn('id', $request->ids)->get();

        foreach ($files as $file) {
            $file->deleteFromStorage();
            $file->forceDelete();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', count($request->ids) . ' fichier(s) supprimé(s).');
    }

    /**
     * Move files to a folder.
     */
    public function move(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_files,id',
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        MediaFile::whereIn('id', $request->ids)
            ->update(['folder_id' => $request->folder_id]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Fichiers déplacés.');
    }

    /**
     * Create a folder.
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media_folders,id',
            'color' => 'nullable|string|max:7',
        ]);

        $folder = MediaFolder::create([
            'user_id' => $request->user()->id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'color' => $request->color,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'folder' => $folder]);
        }

        return back()->with('success', 'Dossier créé.');
    }

    /**
     * Update a folder.
     */
    public function updateFolder(Request $request, MediaFolder $folder)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $folder->update($request->only(['name', 'color']));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'folder' => $folder]);
        }

        return back()->with('success', 'Dossier mis à jour.');
    }

    /**
     * Delete a folder.
     */
    public function destroyFolder(Request $request, MediaFolder $folder)
    {
        // Delete all files in folder and subfolders
        $this->deleteFolderContents($folder);
        $folder->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Dossier supprimé.');
    }

    /**
     * Get folder tree for navigation.
     */
    public function folderTree()
    {
        $folders = MediaFolder::whereNull('parent_id')
            ->with('descendants')
            ->orderBy('name')
            ->get();

        return response()->json(['folders' => $folders]);
    }

    /**
     * Download a file.
     */
    public function download(MediaFile $media)
    {
        return Storage::disk($media->disk)->download($media->path, $media->original_name);
    }

    /**
     * Get storage stats.
     */
    protected function getStorageStats(): array
    {
        $totalSize = MediaFile::sum('size');
        $fileCount = MediaFile::count();
        $imageCount = MediaFile::ofType('image')->count();
        $documentCount = MediaFile::ofType('document')->count();

        return [
            'total_size' => $totalSize,
            'total_size_human' => $this->formatBytes($totalSize),
            'file_count' => $fileCount,
            'image_count' => $imageCount,
            'document_count' => $documentCount,
        ];
    }

    /**
     * Format bytes to human readable.
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'Ko', 'Mo', 'Go', 'To'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Recursively delete folder contents.
     */
    protected function deleteFolderContents(MediaFolder $folder): void
    {
        // Delete files
        foreach ($folder->files as $file) {
            $file->deleteFromStorage();
            $file->forceDelete();
        }

        // Recursively delete subfolders
        foreach ($folder->children as $child) {
            $this->deleteFolderContents($child);
            $child->delete();
        }
    }
}
