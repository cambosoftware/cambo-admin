<?php

namespace CamboSoftware\CamboAdmin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CrudCommand extends Command
{
    protected $signature = 'cambo:crud
                            {name : The name of the model (e.g., Product)}
                            {--fields= : Fields definition (e.g., "name:string,price:decimal,active:boolean")}
                            {--soft-deletes : Add soft deletes}
                            {--api : Generate API routes}
                            {--force : Overwrite existing files}';

    protected $description = 'Generate a complete CRUD (Migration, Model, Controller, Vue Pages)';

    protected Filesystem $files;
    protected string $modelName;
    protected string $modelNamePlural;
    protected string $modelNameLower;
    protected string $modelNamePluralLower;
    protected array $fields = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->modelName = Str::studly($this->argument('name'));
        $this->modelNamePlural = Str::plural($this->modelName);
        $this->modelNameLower = Str::camel($this->modelName);
        $this->modelNamePluralLower = Str::camel($this->modelNamePlural);

        $this->parseFields();

        $this->info("Generating CRUD for {$this->modelName}...");

        $this->generateMigration();
        $this->generateModel();
        $this->generateController();
        $this->generateVuePages();
        $this->addRoutes();

        $this->newLine();
        $this->info("✓ CRUD for {$this->modelName} generated successfully!");
        $this->newLine();
        $this->line('Next steps:');
        $this->line("  1. Review the migration: database/migrations/*_create_" . Str::snake($this->modelNamePlural) . "_table.php");
        $this->line("  2. Run: php artisan migrate");
        $this->line("  3. Visit: /" . Str::kebab($this->modelNamePlural));

        return self::SUCCESS;
    }

    protected function parseFields(): void
    {
        $fieldsOption = $this->option('fields');
        if (!$fieldsOption) {
            return;
        }

        $fieldPairs = explode(',', $fieldsOption);
        foreach ($fieldPairs as $pair) {
            $parts = explode(':', trim($pair));
            if (count($parts) >= 2) {
                $this->fields[$parts[0]] = $parts[1];
            }
        }
    }

    protected function generateMigration(): void
    {
        $tableName = Str::snake($this->modelNamePlural);
        $className = 'Create' . $this->modelNamePlural . 'Table';

        $fieldsCode = '';
        foreach ($this->fields as $name => $type) {
            $fieldsCode .= $this->getMigrationFieldCode($name, $type);
        }

        if ($this->option('soft-deletes')) {
            $fieldsCode .= "            \$table->softDeletes();\n";
        }

        $stub = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
{$fieldsCode}            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
};
PHP;

        $filename = date('Y_m_d_His') . "_create_{$tableName}_table.php";
        $path = database_path("migrations/{$filename}");

        if (!$this->option('force') && $this->files->exists($path)) {
            $this->warn("Migration already exists: {$filename}");
            return;
        }

        $this->files->put($path, $stub);
        $this->info("✓ Migration created: {$filename}");
    }

    protected function getMigrationFieldCode(string $name, string $type): string
    {
        $nullable = Str::endsWith($type, '?');
        $type = rtrim($type, '?');

        $code = match ($type) {
            'string' => "\$table->string('{$name}')",
            'text' => "\$table->text('{$name}')",
            'integer', 'int' => "\$table->integer('{$name}')",
            'bigint' => "\$table->bigInteger('{$name}')",
            'decimal' => "\$table->decimal('{$name}', 10, 2)",
            'float' => "\$table->float('{$name}')",
            'boolean', 'bool' => "\$table->boolean('{$name}')",
            'date' => "\$table->date('{$name}')",
            'datetime' => "\$table->dateTime('{$name}')",
            'timestamp' => "\$table->timestamp('{$name}')",
            'json' => "\$table->json('{$name}')",
            default => "\$table->string('{$name}')",
        };

        if ($nullable) {
            $code .= '->nullable()';
        }

        if ($type === 'boolean') {
            $code .= '->default(false)';
        }

        return "            {$code};\n";
    }

    protected function generateModel(): void
    {
        $tableName = Str::snake($this->modelNamePlural);
        $fillable = array_keys($this->fields);
        $fillableStr = "'" . implode("', '", $fillable) . "'";

        $casts = [];
        foreach ($this->fields as $name => $type) {
            $type = rtrim($type, '?');
            $cast = match ($type) {
                'boolean', 'bool' => 'boolean',
                'integer', 'int', 'bigint' => 'integer',
                'decimal', 'float' => 'float',
                'date' => 'date',
                'datetime', 'timestamp' => 'datetime',
                'json' => 'array',
                default => null,
            };
            if ($cast) {
                $casts[$name] = $cast;
            }
        }

        $castsStr = '';
        if (!empty($casts)) {
            $castsStr = "\n    protected \$casts = [\n";
            foreach ($casts as $name => $cast) {
                $castsStr .= "        '{$name}' => '{$cast}',\n";
            }
            $castsStr .= "    ];\n";
        }

        $softDeletesUse = $this->option('soft-deletes') ? "use Illuminate\\Database\\Eloquent\\SoftDeletes;\n" : '';
        $softDeletesTrait = $this->option('soft-deletes') ? "use SoftDeletes;\n\n    " : '';

        $stub = <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
{$softDeletesUse}
class {$this->modelName} extends Model
{
    use HasFactory;
    {$softDeletesTrait}protected \$fillable = [{$fillableStr}];
{$castsStr}}
PHP;

        $path = app_path("Models/{$this->modelName}.php");

        if (!$this->option('force') && $this->files->exists($path)) {
            $this->warn("Model already exists: {$this->modelName}.php");
            return;
        }

        $this->files->put($path, $stub);
        $this->info("✓ Model created: {$this->modelName}.php");
    }

    protected function generateController(): void
    {
        $stub = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Models\\{$this->modelName};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class {$this->modelName}Controller extends Controller
{
    public function index(Request \$request): Response
    {
        \$query = {$this->modelName}::query();

        if (\$search = \$request->get('search')) {
            \$query->where(function (\$q) use (\$search) {
                // Add searchable fields here
            });
        }

        if (\$sort = \$request->get('sort')) {
            \$direction = str_starts_with(\$sort, '-') ? 'desc' : 'asc';
            \$column = ltrim(\$sort, '-');
            \$query->orderBy(\$column, \$direction);
        } else {
            \$query->latest();
        }

        return Inertia::render('{$this->modelNamePlural}/Index', [
            '{$this->modelNamePluralLower}' => \$query->paginate(\$request->get('per_page', 25)),
            'filters' => \$request->only(['search', 'sort']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('{$this->modelNamePlural}/Create');
    }

    public function store(Request \$request)
    {
        \$validated = \$request->validate([
            // Add validation rules here
        ]);

        {$this->modelName}::create(\$validated);

        return redirect()
            ->route('{$this->modelNamePluralLower}.index')
            ->with('success', '{$this->modelName} created successfully.');
    }

    public function show({$this->modelName} \${$this->modelNameLower}): Response
    {
        return Inertia::render('{$this->modelNamePlural}/Show', [
            '{$this->modelNameLower}' => \${$this->modelNameLower},
        ]);
    }

    public function edit({$this->modelName} \${$this->modelNameLower}): Response
    {
        return Inertia::render('{$this->modelNamePlural}/Edit', [
            '{$this->modelNameLower}' => \${$this->modelNameLower},
        ]);
    }

    public function update(Request \$request, {$this->modelName} \${$this->modelNameLower})
    {
        \$validated = \$request->validate([
            // Add validation rules here
        ]);

        \${$this->modelNameLower}->update(\$validated);

        return redirect()
            ->route('{$this->modelNamePluralLower}.index')
            ->with('success', '{$this->modelName} updated successfully.');
    }

    public function destroy({$this->modelName} \${$this->modelNameLower})
    {
        \${$this->modelNameLower}->delete();

        return redirect()
            ->route('{$this->modelNamePluralLower}.index')
            ->with('success', '{$this->modelName} deleted successfully.');
    }
}
PHP;

        $path = app_path("Http/Controllers/{$this->modelName}Controller.php");

        if (!$this->option('force') && $this->files->exists($path)) {
            $this->warn("Controller already exists: {$this->modelName}Controller.php");
            return;
        }

        $this->files->put($path, $stub);
        $this->info("✓ Controller created: {$this->modelName}Controller.php");
    }

    protected function generateVuePages(): void
    {
        $pagesDir = resource_path("js/Pages/{$this->modelNamePlural}");

        if (!$this->files->isDirectory($pagesDir)) {
            $this->files->makeDirectory($pagesDir, 0755, true);
        }

        $this->generateIndexPage($pagesDir);
        $this->generateCreatePage($pagesDir);
        $this->generateEditPage($pagesDir);
        $this->generateShowPage($pagesDir);

        $this->info("✓ Vue pages created in: Pages/{$this->modelNamePlural}/");
    }

    protected function generateIndexPage(string $dir): void
    {
        $routePrefix = Str::kebab($this->modelNamePlural);

        $stub = <<<VUE
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import Button from '@/Components/UI/Button.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import { PlusIcon, PencilIcon, EyeIcon, TrashIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    {$this->modelNamePluralLower}: Object,
    filters: Object,
})

const search = ref(props.filters?.search || '')
const deleteModal = ref(false)
const itemToDelete = ref(null)

const doSearch = () => {
    router.get('/{$routePrefix}', { search: search.value }, { preserveState: true })
}

const confirmDelete = (item) => {
    itemToDelete.value = item
    deleteModal.value = true
}

const doDelete = () => {
    router.delete(\`/{$routePrefix}/\${itemToDelete.value.id}\`, {
        onSuccess: () => {
            deleteModal.value = false
            itemToDelete.value = null
        }
    })
}
</script>

<template>
    <AdminLayout title="{$this->modelNamePlural}">
        <PageHeader title="{$this->modelNamePlural}" subtitle="Manage your {$this->modelNamePluralLower}">
            <template #actions>
                <Button variant="primary" :icon-left="PlusIcon" :href="\`/{$routePrefix}/create\`">
                    Add {$this->modelName}
                </Button>
            </template>
        </PageHeader>

        <Card>
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <SearchInput v-model="search" placeholder="Search..." @search="doSearch" />
            </div>

            <Table>
                <TableHead>
                    <TableCell header>ID</TableCell>
                    <TableCell header>Created</TableCell>
                    <TableCell header class="text-right">Actions</TableCell>
                </TableHead>
                <TableBody>
                    <TableRow v-for="item in {$this->modelNamePluralLower}.data" :key="item.id">
                        <TableCell>{{ item.id }}</TableCell>
                        <TableCell>{{ item.created_at }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="sm" :icon-left="EyeIcon" :href="\`/{$routePrefix}/\${item.id}\`" />
                                <Button variant="ghost" size="sm" :icon-left="PencilIcon" :href="\`/{$routePrefix}/\${item.id}/edit\`" />
                                <Button variant="ghost" size="sm" :icon-left="TrashIcon" class="text-red-600" @click="confirmDelete(item)" />
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <Pagination :data="{$this->modelNamePluralLower}" />
            </div>
        </Card>

        <ConfirmModal
            :show="deleteModal"
            title="Delete {$this->modelName}"
            message="Are you sure you want to delete this item? This action cannot be undone."
            confirm-text="Delete"
            variant="danger"
            @close="deleteModal = false"
            @confirm="doDelete"
        />
    </AdminLayout>
</template>
VUE;

        $this->files->put("{$dir}/Index.vue", $stub);
    }

    protected function generateCreatePage(string $dir): void
    {
        $routePrefix = Str::kebab($this->modelNamePlural);

        $stub = <<<VUE
<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Button from '@/Components/UI/Button.vue'

const form = useForm({
    // Add form fields here
})

const submit = () => {
    form.post('/{$routePrefix}')
}
</script>

<template>
    <AdminLayout title="Create {$this->modelName}">
        <PageHeader
            title="Create {$this->modelName}"
            subtitle="Add a new {$this->modelNameLower}"
            :back-url="\`/{$routePrefix}\`"
        />

        <Card>
            <Form @submit="submit" class="p-6 space-y-6">
                <!-- Add form fields here -->
                <FormGroup label="Name" :error="form.errors.name">
                    <Input v-model="form.name" placeholder="Enter name" />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary" :href="\`/{$routePrefix}\`">Cancel</Button>
                    <Button type="submit" variant="primary" :loading="form.processing">Create</Button>
                </div>
            </Form>
        </Card>
    </AdminLayout>
</template>
VUE;

        $this->files->put("{$dir}/Create.vue", $stub);
    }

    protected function generateEditPage(string $dir): void
    {
        $routePrefix = Str::kebab($this->modelNamePlural);

        $stub = <<<VUE
<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Form/Form.vue'
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Button from '@/Components/UI/Button.vue'

const props = defineProps({
    {$this->modelNameLower}: Object,
})

const form = useForm({
    // Initialize with existing data
    ...props.{$this->modelNameLower},
})

const submit = () => {
    form.put(\`/{$routePrefix}/\${props.{$this->modelNameLower}.id}\`)
}
</script>

<template>
    <AdminLayout title="Edit {$this->modelName}">
        <PageHeader
            title="Edit {$this->modelName}"
            subtitle="Modify {$this->modelNameLower} details"
            :back-url="\`/{$routePrefix}\`"
        />

        <Card>
            <Form @submit="submit" class="p-6 space-y-6">
                <!-- Add form fields here -->
                <FormGroup label="Name" :error="form.errors.name">
                    <Input v-model="form.name" placeholder="Enter name" />
                </FormGroup>

                <div class="flex justify-end gap-3">
                    <Button variant="secondary" :href="\`/{$routePrefix}\`">Cancel</Button>
                    <Button type="submit" variant="primary" :loading="form.processing">Save Changes</Button>
                </div>
            </Form>
        </Card>
    </AdminLayout>
</template>
VUE;

        $this->files->put("{$dir}/Edit.vue", $stub);
    }

    protected function generateShowPage(string $dir): void
    {
        $routePrefix = Str::kebab($this->modelNamePlural);

        $stub = <<<VUE
<script setup>
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import DescriptionList from '@/Components/Data/DescriptionList.vue'
import Button from '@/Components/UI/Button.vue'
import { PencilIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    {$this->modelNameLower}: Object,
})
</script>

<template>
    <AdminLayout title="{$this->modelName} Details">
        <PageHeader
            :title="\`{$this->modelName} #\${$this->modelNameLower}.id}\`"
            subtitle="View {$this->modelNameLower} details"
            :back-url="\`/{$routePrefix}\`"
        >
            <template #actions>
                <Button variant="primary" :icon-left="PencilIcon" :href="\`/{$routePrefix}/\${$this->modelNameLower}.id}/edit\`">
                    Edit
                </Button>
            </template>
        </PageHeader>

        <Card>
            <div class="p-6">
                <DescriptionList>
                    <template #items>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ {$this->modelNameLower}.id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ {$this->modelNameLower}.created_at }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ {$this->modelNameLower}.updated_at }}</dd>
                        </div>
                    </template>
                </DescriptionList>
            </div>
        </Card>
    </AdminLayout>
</template>
VUE;

        $this->files->put("{$dir}/Show.vue", $stub);
    }

    protected function addRoutes(): void
    {
        $routePrefix = Str::kebab($this->modelNamePlural);
        $controllerClass = "{$this->modelName}Controller";

        $routeCode = <<<PHP

// {$this->modelName} CRUD
Route::resource('{$routePrefix}', App\\Http\\Controllers\\{$controllerClass}::class);
PHP;

        $webRoutesPath = base_path('routes/web.php');

        // Check if routes file exists
        if (!$this->files->exists($webRoutesPath)) {
            $this->warn("Routes file not found: {$webRoutesPath}");
            $this->line("Please add the following routes manually:");
            $this->line($routeCode);
            return;
        }

        $content = $this->files->get($webRoutesPath);

        if (Str::contains($content, "Route::resource('{$routePrefix}'")) {
            $this->warn("Routes for {$routePrefix} already exist.");
            return;
        }

        $this->files->append($webRoutesPath, $routeCode);
        $this->info("✓ Routes added for: {$routePrefix}");
    }
}
