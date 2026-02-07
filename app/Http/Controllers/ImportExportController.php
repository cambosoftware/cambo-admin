<?php

namespace App\Http\Controllers;

use App\Services\ImportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ImportExportController extends Controller
{
    protected ImportExportService $service;

    public function __construct(ImportExportService $service)
    {
        $this->service = $service;
    }

    /**
     * Show import/export page for a resource
     */
    public function index(Request $request)
    {
        $resource = $request->get('resource', 'users');

        return Inertia::render('ImportExport/Index', [
            'resource' => $resource,
            'columns' => $this->getColumnsForResource($resource),
            'availableResources' => $this->getAvailableResources(),
        ]);
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        $request->validate([
            'resource' => 'required|string',
            'format' => 'required|in:csv,excel,pdf',
            'columns' => 'required|array',
            'columns.*' => 'required|string',
            'filters' => 'nullable|array',
        ]);

        $resource = $request->resource;
        $format = $request->format;
        $selectedColumns = $request->columns;
        $filters = $request->filters ?? [];

        // Get data based on resource
        $data = $this->getDataForResource($resource, $filters);
        $columns = $this->filterColumns($this->getColumnsForResource($resource), $selectedColumns);

        // Generate export
        $path = match ($format) {
            'csv' => $this->service->exportToCsv($data, $columns),
            'excel' => $this->service->exportToExcel($data, $columns),
            'pdf' => $this->service->exportToPdf($data, $columns, [
                'title' => ucfirst($resource),
                'subtitle' => 'Export du ' . date('d/m/Y à H:i'),
            ]),
        };

        // Return download URL
        return response()->json([
            'success' => true,
            'download_url' => route('import-export.download', ['path' => base64_encode($path)]),
            'filename' => basename($path),
        ]);
    }

    /**
     * Download exported file
     */
    public function download(Request $request)
    {
        $path = base64_decode($request->path);

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download($path);
    }

    /**
     * Upload file for import
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('imports');

        $preview = $this->service->previewImport($path);

        return response()->json([
            'success' => true,
            'file_path' => $path,
            'headers' => $preview['headers'],
            'preview' => $preview['preview'],
            'total_rows' => $preview['total_rows'],
        ]);
    }

    /**
     * Process import
     */
    public function import(Request $request)
    {
        $request->validate([
            'resource' => 'required|string',
            'file_path' => 'required|string',
            'mapping' => 'required|array',
        ]);

        $resource = $request->resource;
        $filePath = $request->file_path;
        $mapping = $request->mapping;

        // Get model and validation rules
        $modelClass = $this->getModelForResource($resource);
        $rules = $this->getValidationRulesForResource($resource);

        // Process import
        $result = $this->service->importFromCsv($filePath, $mapping, $rules, $modelClass);

        // Clean up uploaded file
        Storage::disk('local')->delete($filePath);

        return response()->json([
            'success' => true,
            'imported' => $result['success'],
            'errors' => $result['errors'],
            'error_details' => $result['error_details'],
        ]);
    }

    /**
     * Download import template
     */
    public function template(Request $request)
    {
        $resource = $request->get('resource', 'users');
        $columns = $this->getColumnsForResource($resource);

        $path = $this->service->generateTemplate($columns, "template_{$resource}.csv");

        return Storage::disk('local')->download($path);
    }

    /**
     * Get columns configuration for a resource
     */
    protected function getColumnsForResource(string $resource): array
    {
        return match ($resource) {
            'users' => [
                ['key' => 'name', 'label' => 'Nom', 'type' => 'text', 'required' => true],
                ['key' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ['key' => 'role', 'label' => 'Rôle', 'type' => 'text'],
                ['key' => 'active', 'label' => 'Actif', 'type' => 'boolean'],
                ['key' => 'created_at', 'label' => 'Date de création', 'type' => 'datetime'],
            ],
            'roles' => [
                ['key' => 'name', 'label' => 'Nom', 'type' => 'text', 'required' => true],
                ['key' => 'description', 'label' => 'Description', 'type' => 'text'],
                ['key' => 'is_default', 'label' => 'Par défaut', 'type' => 'boolean'],
                ['key' => 'users_count', 'label' => 'Nombre d\'utilisateurs', 'type' => 'integer'],
            ],
            'activities' => [
                ['key' => 'description', 'label' => 'Description', 'type' => 'text'],
                ['key' => 'causer.name', 'label' => 'Utilisateur', 'type' => 'text'],
                ['key' => 'subject_type', 'label' => 'Type', 'type' => 'text'],
                ['key' => 'event', 'label' => 'Événement', 'type' => 'text'],
                ['key' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
            ],
            default => [],
        };
    }

    /**
     * Get data for resource
     */
    protected function getDataForResource(string $resource, array $filters = [])
    {
        return match ($resource) {
            'users' => \App\Models\User::when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->get(),
            'roles' => \App\Models\Role::withCount('users')->get(),
            'activities' => \App\Models\Activity::with('causer')->latest()->limit(1000)->get(),
            default => collect([]),
        };
    }

    /**
     * Get model class for resource
     */
    protected function getModelForResource(string $resource): ?string
    {
        return match ($resource) {
            'users' => \App\Models\User::class,
            'roles' => \App\Models\Role::class,
            default => null,
        };
    }

    /**
     * Get validation rules for resource
     */
    protected function getValidationRulesForResource(string $resource): array
    {
        return match ($resource) {
            'users' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ],
            'roles' => [
                'name' => 'required|string|max:255|unique:roles,name',
            ],
            default => [],
        };
    }

    /**
     * Get available resources
     */
    protected function getAvailableResources(): array
    {
        return [
            ['value' => 'users', 'label' => 'Utilisateurs'],
            ['value' => 'roles', 'label' => 'Rôles'],
            ['value' => 'activities', 'label' => 'Activités'],
        ];
    }

    /**
     * Filter columns based on selection
     */
    protected function filterColumns(array $columns, array $selected): array
    {
        return array_values(array_filter($columns, fn($col) => in_array($col['key'], $selected)));
    }
}
