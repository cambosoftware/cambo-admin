# Data Import/Export

CamboAdmin provides tools for importing and exporting data in various formats.

## Overview

The import/export module supports:

- **Export formats**: CSV, Excel (XLSX), PDF
- **Import formats**: CSV, Excel (XLSX)
- **Features**: Column selection, filtering, validation

## Configuration

Enable the module:

```php
// config/cambo-admin.php
'modules' => [
    'import-export' => true,
],

'exports' => [
    'csv' => true,
    'excel' => true,  // Requires maatwebsite/excel
    'pdf' => true,    // Requires barryvdh/laravel-dompdf
],
```

## Installing Dependencies

```bash
# For Excel support
composer require maatwebsite/excel

# For PDF support
composer require barryvdh/laravel-dompdf
```

## Using the ImportExportService

```php
use CamboSoftware\CamboAdmin\Services\ImportExportService;

class UserController extends Controller
{
    public function __construct(
        protected ImportExportService $importExport
    ) {}
}
```

## Exporting Data

### CSV Export

```php
public function exportCsv()
{
    $users = User::all();

    return $this->importExport->exportCsv(
        $users,
        ['id', 'name', 'email', 'created_at'],
        'users.csv'
    );
}
```

### Excel Export

```php
public function exportExcel()
{
    $users = User::all();

    return $this->importExport->exportExcel(
        $users,
        ['id', 'name', 'email', 'created_at'],
        'users.xlsx'
    );
}
```

### PDF Export

```php
public function exportPdf()
{
    $users = User::all();

    return $this->importExport->exportPdf(
        $users,
        ['id', 'name', 'email'],
        'users.pdf',
        'exports.users' // Blade view for PDF template
    );
}
```

## Importing Data

### Basic Import

```php
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx',
    ]);

    $results = $this->importExport->import(
        $request->file('file'),
        User::class,
        ['name', 'email', 'password']
    );

    return back()->with('success', "Imported {$results['success']} records.");
}
```

### With Validation

```php
$results = $this->importExport->import(
    $file,
    User::class,
    ['name', 'email', 'password'],
    [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]
);

if ($results['errors']) {
    return back()->with('errors', $results['errors']);
}
```

### With Transformation

```php
$results = $this->importExport->import(
    $file,
    User::class,
    ['name', 'email', 'password'],
    [],
    function ($row) {
        $row['password'] = bcrypt($row['password']);
        $row['email'] = strtolower($row['email']);
        return $row;
    }
);
```

## Export with Filters

```php
public function export(Request $request)
{
    $query = User::query();

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->from) {
        $query->where('created_at', '>=', $request->from);
    }

    if ($request->to) {
        $query->where('created_at', '<=', $request->to);
    }

    $format = $request->format ?? 'csv';
    $columns = $request->columns ?? ['id', 'name', 'email'];

    return match($format) {
        'csv' => $this->importExport->exportCsv($query->get(), $columns, 'users.csv'),
        'excel' => $this->importExport->exportExcel($query->get(), $columns, 'users.xlsx'),
        'pdf' => $this->importExport->exportPdf($query->get(), $columns, 'users.pdf'),
    };
}
```

## Vue Export Component

```vue
<template>
  <Dropdown>
    <template #trigger>
      <Button variant="secondary">
        <ArrowDownTrayIcon class="w-4 h-4 mr-2" />
        Export
      </Button>
    </template>

    <DropdownItem @click="exportData('csv')">
      Export as CSV
    </DropdownItem>
    <DropdownItem @click="exportData('excel')">
      Export as Excel
    </DropdownItem>
    <DropdownItem @click="exportData('pdf')">
      Export as PDF
    </DropdownItem>
  </Dropdown>
</template>

<script setup>
const exportData = (format) => {
  window.location.href = route('users.export', {
    format,
    ...currentFilters
  })
}
</script>
```

## Vue Import Component

```vue
<template>
  <Modal v-model="showImport" title="Import Users">
    <Form @submit="submitImport">
      <FormGroup label="File" :error="form.errors.file">
        <FileDropzone
          v-model="form.file"
          accept=".csv,.xlsx"
          :max-size="5 * 1024 * 1024"
        />
      </FormGroup>

      <Alert v-if="importResults" :variant="importResults.errors ? 'warning' : 'success'">
        <p>Imported {{ importResults.success }} records.</p>
        <p v-if="importResults.errors">
          {{ importResults.errors.length }} errors occurred.
        </p>
      </Alert>
    </Form>

    <template #footer>
      <Button variant="secondary" @click="showImport = false">Cancel</Button>
      <Button variant="primary" :loading="form.processing" @click="submitImport">
        Import
      </Button>
    </template>
  </Modal>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({ file: null })
const importResults = ref(null)

const submitImport = () => {
  form.post(route('users.import'), {
    onSuccess: (page) => {
      importResults.value = page.props.flash.importResults
    }
  })
}
</script>
```

## PDF Template Example

```blade
{{-- resources/views/exports/users.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #6366f1; color: white; }
    </style>
</head>
<body>
    <h1>Users Export</h1>
    <p>Generated: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ ucfirst($column) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($columns as $column)
                        <td>{{ $row[$column] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```
