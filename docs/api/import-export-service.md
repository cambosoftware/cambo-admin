# ImportExportService

The `ImportExportService` handles data import and export operations for CSV, Excel, and PDF formats.

## Description

ImportExportService provides a complete data import/export system with support for CSV, Excel (via CSV compatibility), and PDF formats. It includes validation, column mapping, data transformation, and error handling.

## Usage

```php
use CamboSoftware\CamboAdmin\Services\ImportExportService;

$importExportService = app(ImportExportService::class);
```

## Methods

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `exportToCsv()` | `Collection $data, array $columns, ?string $filename` | `string` | Export data to CSV |
| `exportToExcel()` | `Collection $data, array $columns, ?string $filename` | `string` | Export data to Excel |
| `exportToPdf()` | `Collection $data, array $columns, array $options` | `string` | Export data to PDF |
| `importFromCsv()` | `string $filePath, array $mapping, array $rules, ?string $model` | `array` | Import data from CSV |
| `previewImport()` | `string $filePath, int $limit` | `array` | Preview import data |
| `generateTemplate()` | `array $columns, ?string $filename` | `string` | Generate import template |

## Method Details

### exportToCsv()

```php
public function exportToCsv(Collection $data, array $columns, string $filename = null): string
```

Exports data to a CSV file with BOM for Excel compatibility.

**Parameters:**
- `$data` - Collection of data to export
- `$columns` - Array of column definitions
- `$filename` - Optional filename (defaults to timestamped name)

**Column Definition Format:**
```php
[
    ['key' => 'id', 'label' => 'ID'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'email', 'label' => 'Email'],
    ['key' => 'created_at', 'label' => 'Created At', 'type' => 'datetime'],
]
```

**Example:**

```php
$users = User::all();
$columns = [
    ['key' => 'id', 'label' => 'ID'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'email', 'label' => 'Email'],
    ['key' => 'is_active', 'label' => 'Active', 'type' => 'boolean'],
    ['key' => 'created_at', 'label' => 'Created At', 'type' => 'datetime'],
];

$path = $importExportService->exportToCsv($users, $columns, 'users.csv');
// Returns: 'exports/users.csv'
```

### exportToExcel()

```php
public function exportToExcel(Collection $data, array $columns, string $filename = null): string
```

Exports data to Excel format (via CSV with Excel-compatible encoding).

**Note:** For full Excel support (XLSX), install `PhpSpreadsheet`.

**Example:**

```php
$path = $importExportService->exportToExcel($users, $columns, 'users.xlsx');
```

### exportToPdf()

```php
public function exportToPdf(Collection $data, array $columns, array $options = []): string
```

Exports data to PDF format. Requires `barryvdh/laravel-dompdf` package.

**Parameters:**
- `$data` - Collection of data
- `$columns` - Column definitions
- `$options` - PDF options (title, subtitle, filename, orientation)

**Options:**
```php
[
    'title' => 'User Report',
    'subtitle' => 'Generated on 2024-01-15',
    'filename' => 'users_report.pdf',
    'orientation' => 'landscape', // or 'portrait'
]
```

**Example:**

```php
$path = $importExportService->exportToPdf($users, $columns, [
    'title' => 'User List',
    'subtitle' => 'As of ' . now()->format('d/m/Y'),
    'orientation' => 'landscape',
]);
```

### importFromCsv()

```php
public function importFromCsv(string $filePath, array $mapping, array $rules = [], string $model = null): array
```

Imports data from a CSV file with validation and optional model creation.

**Parameters:**
- `$filePath` - Path to CSV file in storage
- `$mapping` - Column mapping (target => source)
- `$rules` - Laravel validation rules
- `$model` - Optional model class for automatic creation

**Mapping Format:**
```php
// Simple mapping
[
    'name' => 'Full Name',      // target => CSV column
    'email' => 'Email Address',
]

// With transformation
[
    'name' => 'Full Name',
    'is_active' => [
        'column' => 'Status',
        'transform' => 'boolean',
    ],
    'birth_date' => [
        'column' => 'Date of Birth',
        'transform' => 'date',
    ],
]
```

**Transform Types:**
- `boolean` - Converts 'yes', 'true', '1', 'oui' to true
- `integer` - Casts to integer
- `float`, `decimal` - Casts to float
- `date` - Parses various date formats
- `lowercase` - Converts to lowercase
- `uppercase` - Converts to uppercase
- `trim` - Trims whitespace
- `slug` - Converts to slug

**Example:**

```php
$result = $importExportService->importFromCsv(
    'imports/users.csv',
    [
        'name' => 'Full Name',
        'email' => 'Email',
        'is_active' => ['column' => 'Active', 'transform' => 'boolean'],
    ],
    [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ],
    User::class
);

// Returns:
// [
//     'success' => 45,
//     'errors' => 5,
//     'error_details' => [...],
//     'warnings' => [...],
//     'data' => [...],
// ]
```

### previewImport()

```php
public function previewImport(string $filePath, int $limit = 10): array
```

Returns a preview of import data for validation before actual import.

**Parameters:**
- `$filePath` - Path to CSV file
- `$limit` - Number of rows to preview (default: 10)

**Example:**

```php
$preview = $importExportService->previewImport('imports/users.csv', 5);
// Returns:
// [
//     'headers' => ['Full Name', 'Email', 'Active'],
//     'preview' => [...first 5 rows...],
//     'total_rows' => 150,
// ]
```

### generateTemplate()

```php
public function generateTemplate(array $columns, string $filename = null): string
```

Generates a CSV template with headers and example data for import.

**Parameters:**
- `$columns` - Column definitions with types
- `$filename` - Optional filename

**Example:**

```php
$path = $importExportService->generateTemplate([
    ['key' => 'name', 'label' => 'Full Name', 'type' => 'text'],
    ['key' => 'email', 'label' => 'Email', 'type' => 'email'],
    ['key' => 'birth_date', 'label' => 'Date of Birth', 'type' => 'date'],
    ['key' => 'is_active', 'label' => 'Active', 'type' => 'boolean'],
]);
// Returns: 'exports/template_2024-01-15.csv'
// File contains headers and example row showing expected format
```

## Value Formatting

The service automatically formats values based on type:

| Type | Export Format | Example |
|------|--------------|---------|
| `boolean` | Yes/No | `true` => `Yes` |
| `date` | DD/MM/YYYY | `2024-01-15` => `15/01/2024` |
| `datetime` | DD/MM/YYYY HH:MM | `2024-01-15 14:30` => `15/01/2024 14:30` |
| `price`, `currency` | Number with currency | `1234.50` => `1 234,50 EUR` |
| `percent` | Number with % | `75.5` => `75,5 %` |

## Complete Usage Example

```php
use CamboSoftware\CamboAdmin\Services\ImportExportService;
use Illuminate\Http\Request;

class UserImportExportController extends Controller
{
    public function __construct(
        protected ImportExportService $importExportService
    ) {}

    public function export(Request $request)
    {
        $users = User::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        $columns = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'boolean'],
            ['key' => 'created_at', 'label' => 'Created At', 'type' => 'datetime'],
        ];

        $format = $request->format ?? 'csv';

        $path = match($format) {
            'pdf' => $this->importExportService->exportToPdf($users, $columns, [
                'title' => 'User Export',
                'orientation' => 'landscape',
            ]),
            'excel' => $this->importExportService->exportToExcel($users, $columns),
            default => $this->importExportService->exportToCsv($users, $columns),
        };

        return Storage::disk('local')->download($path);
    }

    public function template()
    {
        $path = $this->importExportService->generateTemplate([
            ['key' => 'name', 'label' => 'Full Name', 'type' => 'text'],
            ['key' => 'email', 'label' => 'Email', 'type' => 'email'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'boolean'],
        ]);

        return Storage::disk('local')->download($path);
    }

    public function preview(Request $request)
    {
        $path = $request->file('file')->store('imports');

        return response()->json(
            $this->importExportService->previewImport($path)
        );
    }

    public function import(Request $request)
    {
        $path = $request->file('file')->store('imports');

        $result = $this->importExportService->importFromCsv(
            $path,
            [
                'name' => 'Full Name',
                'email' => 'Email',
                'is_active' => ['column' => 'Active', 'transform' => 'boolean'],
            ],
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ],
            User::class
        );

        return response()->json($result);
    }
}
```

## PDF Requirements

For PDF export, install DomPDF:

```bash
composer require barryvdh/laravel-dompdf
```

The service will throw a `RuntimeException` if PDF export is attempted without DomPDF installed.

## Source Code

**Location:** `src/Services/ImportExportService.php`

**Namespace:** `CamboSoftware\CamboAdmin\Services`

**Storage:** Files are stored in `storage/app/exports/` directory
