<?php

namespace CamboSoftware\CamboAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImportExportService
{
    protected array $errors = [];
    protected array $warnings = [];
    protected int $successCount = 0;
    protected int $errorCount = 0;

    /**
     * Export data to CSV
     */
    public function exportToCsv(Collection $data, array $columns, string $filename = null): string
    {
        $filename = $filename ?? 'export_' . date('Y-m-d_His') . '.csv';
        $path = 'exports/' . $filename;

        $csv = fopen('php://temp', 'r+');

        // Headers
        $headers = array_map(fn($col) => $col['label'] ?? $col['key'], $columns);
        fputcsv($csv, $headers);

        // Data rows
        foreach ($data as $row) {
            $values = [];
            foreach ($columns as $col) {
                $key = $col['key'];
                $value = data_get($row, $key, '');

                // Format value based on type
                if (isset($col['type'])) {
                    $value = $this->formatValueForExport($value, $col['type']);
                }

                $values[] = $value;
            }
            fputcsv($csv, $values);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        // Add BOM for Excel compatibility
        $content = "\xEF\xBB\xBF" . $content;

        Storage::disk('local')->put($path, $content);

        return $path;
    }

    /**
     * Export data to Excel (XLSX)
     */
    public function exportToExcel(Collection $data, array $columns, string $filename = null): string
    {
        // For Excel export, we'll use a simple CSV with .xlsx extension
        // In a full implementation, you'd use PhpSpreadsheet
        $filename = $filename ?? 'export_' . date('Y-m-d_His') . '.xlsx';

        // Simplified: create a CSV that Excel can open
        return $this->exportToCsv($data, $columns, str_replace('.xlsx', '.csv', $filename));
    }

    /**
     * Export data to PDF
     * Note: Requires barryvdh/laravel-dompdf package
     */
    public function exportToPdf(Collection $data, array $columns, array $options = []): string
    {
        // Check if DomPDF is available
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            throw new \RuntimeException('PDF export requires barryvdh/laravel-dompdf package. Install with: composer require barryvdh/laravel-dompdf');
        }

        $filename = $options['filename'] ?? 'export_' . date('Y-m-d_His') . '.pdf';
        $path = 'exports/' . $filename;

        $html = $this->generatePdfHtml($data, $columns, $options);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);

        if ($options['orientation'] ?? 'portrait' === 'landscape') {
            $pdf->setPaper('a4', 'landscape');
        }

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Generate HTML for PDF export
     */
    protected function generatePdfHtml(Collection $data, array $columns, array $options): string
    {
        $title = $options['title'] ?? 'Export';
        $subtitle = $options['subtitle'] ?? date('d/m/Y H:i');

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{$title}</h1>
        <p>{$subtitle}</p>
    </div>
    <table>
        <thead>
            <tr>
HTML;

        foreach ($columns as $col) {
            $label = htmlspecialchars($col['label'] ?? $col['key']);
            $html .= "<th>{$label}</th>";
        }

        $html .= <<<HTML
            </tr>
        </thead>
        <tbody>
HTML;

        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($columns as $col) {
                $key = $col['key'];
                $value = data_get($row, $key, '');
                $value = $this->formatValueForPdf($value, $col['type'] ?? 'text');
                $html .= "<td>{$value}</td>";
            }
            $html .= '</tr>';
        }

        $recordCount = $data->count();
        $html .= <<<HTML
        </tbody>
    </table>
    <div class="footer">
        Total: {$recordCount} records | Generated on {$subtitle}
    </div>
</body>
</html>
HTML;

        return $html;
    }

    /**
     * Import data from CSV
     */
    public function importFromCsv(string $filePath, array $mapping, array $rules = [], string $model = null): array
    {
        $this->resetCounters();

        $content = Storage::disk('local')->get($filePath);
        // Remove BOM if present
        $content = str_replace("\xEF\xBB\xBF", '', $content);

        $rows = array_map('str_getcsv', explode("\n", $content));
        $headers = array_shift($rows);

        // Clean headers
        $headers = array_map('trim', $headers);

        $results = [];

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) {
                continue; // Skip empty rows
            }

            $rowData = array_combine($headers, array_pad($row, count($headers), ''));
            $mappedData = $this->mapColumns($rowData, $mapping);

            // Validate
            if (!empty($rules)) {
                $validator = Validator::make($mappedData, $rules);

                if ($validator->fails()) {
                    $this->errors[] = [
                        'row' => $index + 2, // +2 for header row and 0-index
                        'data' => $mappedData,
                        'errors' => $validator->errors()->toArray(),
                    ];
                    $this->errorCount++;
                    continue;
                }
            }

            // Create record if model provided
            if ($model && class_exists($model)) {
                try {
                    $model::create($mappedData);
                    $this->successCount++;
                } catch (\Exception $e) {
                    $this->errors[] = [
                        'row' => $index + 2,
                        'data' => $mappedData,
                        'errors' => ['database' => [$e->getMessage()]],
                    ];
                    $this->errorCount++;
                }
            } else {
                $results[] = $mappedData;
                $this->successCount++;
            }
        }

        return [
            'success' => $this->successCount,
            'errors' => $this->errorCount,
            'error_details' => $this->errors,
            'warnings' => $this->warnings,
            'data' => $results,
        ];
    }

    /**
     * Preview import data (first N rows)
     */
    public function previewImport(string $filePath, int $limit = 10): array
    {
        $content = Storage::disk('local')->get($filePath);
        $content = str_replace("\xEF\xBB\xBF", '', $content);

        $rows = array_map('str_getcsv', explode("\n", $content));
        $headers = array_shift($rows);
        $headers = array_map('trim', $headers);

        $preview = [];
        $count = 0;

        foreach ($rows as $row) {
            if (empty(array_filter($row))) {
                continue;
            }

            if ($count >= $limit) {
                break;
            }

            $preview[] = array_combine($headers, array_pad($row, count($headers), ''));
            $count++;
        }

        return [
            'headers' => $headers,
            'preview' => $preview,
            'total_rows' => count(array_filter($rows, fn($r) => !empty(array_filter($r)))),
        ];
    }

    /**
     * Map columns from source to target
     */
    protected function mapColumns(array $row, array $mapping): array
    {
        $mapped = [];

        foreach ($mapping as $target => $source) {
            if (is_array($source)) {
                // Custom transformation
                $value = $row[$source['column']] ?? null;
                if (isset($source['transform'])) {
                    $value = $this->transformValue($value, $source['transform']);
                }
                $mapped[$target] = $value;
            } else {
                // Direct mapping
                $mapped[$target] = $row[$source] ?? null;
            }
        }

        return $mapped;
    }

    /**
     * Transform value based on type
     */
    protected function transformValue($value, string $transform)
    {
        return match ($transform) {
            'boolean' => in_array(strtolower($value), ['1', 'true', 'yes', 'oui', 'y', 'o']),
            'integer' => (int) $value,
            'float', 'decimal' => (float) str_replace(',', '.', $value),
            'date' => $this->parseDate($value),
            'lowercase' => strtolower($value),
            'uppercase' => strtoupper($value),
            'trim' => trim($value),
            'slug' => Str::slug($value),
            default => $value,
        };
    }

    /**
     * Parse date from various formats
     */
    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $formats = [
            'd/m/Y',
            'd-m-Y',
            'Y-m-d',
            'd/m/Y H:i',
            'd/m/Y H:i:s',
            'Y-m-d H:i:s',
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Format value for export
     */
    protected function formatValueForExport($value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? 'Yes' : 'No',
            'date' => $value ? date('d/m/Y', strtotime($value)) : '',
            'datetime' => $value ? date('d/m/Y H:i', strtotime($value)) : '',
            'price', 'currency' => number_format((float) $value, 2, ',', ' ') . ' €',
            'percent' => number_format((float) $value, 1, ',', '') . ' %',
            default => (string) $value,
        };
    }

    /**
     * Format value for PDF
     */
    protected function formatValueForPdf($value, string $type): string
    {
        $formatted = $this->formatValueForExport($value, $type);

        // Add badges for boolean values
        if ($type === 'boolean') {
            $class = $value ? 'badge-success' : 'badge-danger';
            return "<span class=\"badge {$class}\">{$formatted}</span>";
        }

        // Add status badges
        if ($type === 'status') {
            $statusColors = [
                'active' => 'badge-success',
                'inactive' => 'badge-danger',
                'pending' => 'badge-warning',
                'draft' => 'badge-info',
            ];
            $class = $statusColors[strtolower($value)] ?? 'badge-info';
            return "<span class=\"badge {$class}\">" . htmlspecialchars($value) . "</span>";
        }

        return htmlspecialchars($formatted);
    }

    /**
     * Reset counters
     */
    protected function resetCounters(): void
    {
        $this->errors = [];
        $this->warnings = [];
        $this->successCount = 0;
        $this->errorCount = 0;
    }

    /**
     * Generate template CSV for import
     */
    public function generateTemplate(array $columns, string $filename = null): string
    {
        $filename = $filename ?? 'template_' . date('Y-m-d') . '.csv';
        $path = 'exports/' . $filename;

        $csv = fopen('php://temp', 'r+');

        // Headers
        $headers = array_map(fn($col) => $col['label'] ?? $col['key'], $columns);
        fputcsv($csv, $headers);

        // Example row with descriptions
        $example = array_map(function ($col) {
            $type = $col['type'] ?? 'text';
            return match ($type) {
                'boolean' => 'Yes/No',
                'date' => 'DD/MM/YYYY',
                'datetime' => 'DD/MM/YYYY HH:MM',
                'integer' => '123',
                'decimal', 'float' => '123.45',
                'email' => 'example@email.com',
                default => 'Text',
            };
        }, $columns);
        fputcsv($csv, $example);

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        // Add BOM for Excel compatibility
        $content = "\xEF\xBB\xBF" . $content;

        Storage::disk('local')->put($path, $content);

        return $path;
    }
}
