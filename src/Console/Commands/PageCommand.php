<?php

namespace CamboSoftware\CamboAdmin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PageCommand extends Command
{
    protected $signature = 'cambo:page
                            {name : The name of the page (e.g., Reports/Analytics)}
                            {--title= : Page title}
                            {--subtitle= : Page subtitle}
                            {--with-card : Include a Card component}
                            {--with-form : Include a Form structure}
                            {--with-table : Include a Table structure}
                            {--force : Overwrite existing file}';

    protected $description = 'Generate a Vue page with AdminLayout';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $pageName = $this->argument('name');
        $pagePath = resource_path("js/Pages/{$pageName}.vue");
        $pageDir = dirname($pagePath);

        // Create directory if needed
        if (!$this->files->isDirectory($pageDir)) {
            $this->files->makeDirectory($pageDir, 0755, true);
        }

        if (!$this->option('force') && $this->files->exists($pagePath)) {
            $this->error("Page already exists: {$pageName}.vue");
            return self::FAILURE;
        }

        $title = $this->option('title') ?? Str::headline(basename($pageName));
        $subtitle = $this->option('subtitle') ?? '';

        $content = $this->generateContent($title, $subtitle);

        $this->files->put($pagePath, $content);

        $this->info("✓ Page created: {$pageName}.vue");

        return self::SUCCESS;
    }

    protected function generateContent(string $title, string $subtitle): string
    {
        $imports = [
            "import AdminLayout from '@/Components/Layout/AdminLayout.vue'",
            "import PageHeader from '@/Components/Layout/PageHeader.vue'",
        ];

        $template = '';

        if ($this->option('with-card')) {
            $imports[] = "import Card from '@/Components/Containers/Card.vue'";
        }

        if ($this->option('with-form')) {
            $imports[] = "import Form from '@/Components/Form/Form.vue'";
            $imports[] = "import FormGroup from '@/Components/Form/FormGroup.vue'";
            $imports[] = "import Input from '@/Components/Form/Input.vue'";
            $imports[] = "import Button from '@/Components/UI/Button.vue'";
        }

        if ($this->option('with-table')) {
            $imports[] = "import Table from '@/Components/Data/Table.vue'";
            $imports[] = "import TableHead from '@/Components/Data/TableHead.vue'";
            $imports[] = "import TableBody from '@/Components/Data/TableBody.vue'";
            $imports[] = "import TableRow from '@/Components/Data/TableRow.vue'";
            $imports[] = "import TableCell from '@/Components/Data/TableCell.vue'";
            $imports[] = "import Pagination from '@/Components/Data/Pagination.vue'";
        }

        $importsStr = implode("\n", $imports);

        // Build template content
        $innerContent = '';

        if ($this->option('with-card')) {
            if ($this->option('with-form')) {
                $innerContent = $this->getFormContent();
            } elseif ($this->option('with-table')) {
                $innerContent = $this->getTableContent();
            } else {
                $innerContent = <<<HTML
        <Card>
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-400">
                    Page content goes here...
                </p>
            </div>
        </Card>
HTML;
            }
        } elseif ($this->option('with-form')) {
            $innerContent = $this->getFormContent();
        } elseif ($this->option('with-table')) {
            $innerContent = $this->getTableContent();
        } else {
            $innerContent = <<<HTML
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-gray-600 dark:text-gray-400">
                Page content goes here...
            </p>
        </div>
HTML;
        }

        $subtitleAttr = $subtitle ? "\n            subtitle=\"{$subtitle}\"" : '';

        return <<<VUE
<script setup>
{$importsStr}

defineProps({
    // Add props here
})
</script>

<template>
    <AdminLayout title="{$title}">
        <PageHeader
            title="{$title}"{$subtitleAttr}
        />

{$innerContent}
    </AdminLayout>
</template>
VUE;
    }

    protected function getFormContent(): string
    {
        $cardOpen = $this->option('with-card') ? '<Card>' : '<div class="bg-white dark:bg-gray-800 rounded-lg shadow">';
        $cardClose = $this->option('with-card') ? '</Card>' : '</div>';

        return <<<HTML
        {$cardOpen}
            <Form class="p-6 space-y-6">
                <FormGroup label="Field Name" hint="Enter a value">
                    <Input placeholder="Enter value..." />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary">Cancel</Button>
                    <Button variant="primary">Save</Button>
                </div>
            </Form>
        {$cardClose}
HTML;
    }

    protected function getTableContent(): string
    {
        $cardOpen = $this->option('with-card') ? '<Card>' : '<div class="bg-white dark:bg-gray-800 rounded-lg shadow">';
        $cardClose = $this->option('with-card') ? '</Card>' : '</div>';

        return <<<HTML
        {$cardOpen}
            <Table>
                <TableHead>
                    <TableCell header>ID</TableCell>
                    <TableCell header>Name</TableCell>
                    <TableCell header>Status</TableCell>
                    <TableCell header class="text-right">Actions</TableCell>
                </TableHead>
                <TableBody>
                    <TableRow>
                        <TableCell>1</TableCell>
                        <TableCell>Example Item</TableCell>
                        <TableCell>Active</TableCell>
                        <TableCell class="text-right">-</TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <!-- Pagination goes here -->
            </div>
        {$cardClose}
HTML;
    }
}
