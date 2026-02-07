# Quick Start

This tutorial will guide you through building your first CRUD application with CamboAdmin in under 10 minutes. By the end, you'll have a fully functional product management system with listing, create, edit, and delete functionality.

## Prerequisites

Before starting, ensure you have:

- CamboAdmin installed and configured ([Installation Guide](/guide/installation))
- Your development server running (`php artisan serve` and `npm run dev`)
- Access to the admin panel at `http://localhost:8000/admin`

## Step 1: Generate a CRUD

CamboAdmin's CLI makes generating CRUDs incredibly simple. Let's create a Product management system:

```bash
php artisan cambo:crud Product --fields="name:string,description:text,price:decimal,sku:string,stock:integer,active:boolean"
```

This single command generates:

| File | Purpose |
|------|---------|
| `app/Models/Product.php` | Eloquent model with fillable fields |
| `app/Http/Controllers/ProductController.php` | Full CRUD controller |
| `database/migrations/xxxx_create_products_table.php` | Database migration |
| `resources/js/Pages/Products/Index.vue` | Product listing page |
| `resources/js/Pages/Products/Create.vue` | Create product page |
| `resources/js/Pages/Products/Edit.vue` | Edit product page |
| `resources/js/Pages/Products/Show.vue` | View product details |

## Step 2: Run the Migration

Create the database table:

```bash
php artisan migrate
```

This creates the `products` table with all your defined fields plus timestamps.

## Step 3: Add Routes (If Needed)

If you're using CamboAdmin's automatic routing, routes are already registered. Otherwise, add to `routes/web.php`:

```php
use App\Http\Controllers\ProductController;

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class)->names([
        'index' => 'cambo.products.index',
        'create' => 'cambo.products.create',
        'store' => 'cambo.products.store',
        'show' => 'cambo.products.show',
        'edit' => 'cambo.products.edit',
        'update' => 'cambo.products.update',
        'destroy' => 'cambo.products.destroy',
    ]);
});
```

## Step 4: Access Your CRUD

Navigate to `http://localhost:8000/admin/products` to see your new product management interface:

- **Index Page** - DataTable with search, sort, and pagination
- **Create Page** - Form to add new products
- **Edit Page** - Form to modify existing products
- **Show Page** - Detailed view of a product

## Understanding the Generated Code

Let's explore what CamboAdmin generated:

### The Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'sku',
        'stock',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'active' => 'boolean',
    ];
}
```

### The Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->sort, function ($query, $sort) use ($request) {
                $direction = $request->direction === 'desc' ? 'desc' : 'asc';
                $query->orderBy($sort, $direction);
            }, function ($query) {
                $query->latest();
            })
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'sort', 'direction', 'per_page']),
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'stock' => 'required|integer|min:0',
            'active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()
            ->route('cambo.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): Response
    {
        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the product.
     */
    public function edit(Product $product): Response
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()
            ->route('cambo.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('cambo.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
```

### The Index Page (Vue)

```vue
<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import DataTable from '@/Components/DataTable/DataTable.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'

const props = defineProps({
    products: Object,
    filters: Object,
})

const columns = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'name', label: 'Name', sortable: true },
    { key: 'sku', label: 'SKU', sortable: true },
    { key: 'price', label: 'Price', sortable: true },
    { key: 'stock', label: 'Stock', sortable: true },
    { key: 'active', label: 'Status', sortable: true },
    { key: 'actions', label: 'Actions', align: 'right' },
]

const deleteModal = ref(false)
const productToDelete = ref(null)

const confirmDelete = (product) => {
    productToDelete.value = product
    deleteModal.value = true
}

const deleteProduct = () => {
    router.delete(route('cambo.products.destroy', productToDelete.value.id), {
        onSuccess: () => {
            deleteModal.value = false
            productToDelete.value = null
        },
    })
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price)
}
</script>

<template>
    <AppLayout title="Products">
        <PageHeader title="Products" subtitle="Manage your product catalog">
            <template #actions>
                <Button
                    :href="route('cambo.products.create')"
                    variant="primary"
                >
                    Add Product
                </Button>
            </template>
        </PageHeader>

        <DataTable
            :data="products"
            :columns="columns"
            :filters="filters"
            searchable
            :search-placeholder="'Search products...'"
        >
            <template #cell-price="{ value }">
                {{ formatPrice(value) }}
            </template>

            <template #cell-stock="{ value }">
                <Badge :variant="value > 10 ? 'success' : value > 0 ? 'warning' : 'danger'">
                    {{ value }}
                </Badge>
            </template>

            <template #cell-active="{ value }">
                <Badge :variant="value ? 'success' : 'secondary'">
                    {{ value ? 'Active' : 'Inactive' }}
                </Badge>
            </template>

            <template #cell-actions="{ row }">
                <div class="flex items-center justify-end gap-2">
                    <Button
                        :href="route('cambo.products.show', row.id)"
                        variant="ghost"
                        size="sm"
                    >
                        View
                    </Button>
                    <Button
                        :href="route('cambo.products.edit', row.id)"
                        variant="ghost"
                        size="sm"
                    >
                        Edit
                    </Button>
                    <Button
                        variant="ghost"
                        size="sm"
                        class="text-red-600 hover:text-red-700"
                        @click="confirmDelete(row)"
                    >
                        Delete
                    </Button>
                </div>
            </template>
        </DataTable>

        <ConfirmModal
            v-model="deleteModal"
            title="Delete Product"
            message="Are you sure you want to delete this product? This action cannot be undone."
            confirm-text="Delete"
            confirm-variant="danger"
            @confirm="deleteProduct"
        />
    </AppLayout>
</template>
```

### The Create Page (Vue)

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'
import Card from '@/Components/Containers/Card.vue'
import Form from '@/Components/Forms/Form.vue'
import FormGroup from '@/Components/Forms/FormGroup.vue'
import Input from '@/Components/Forms/Input.vue'
import Textarea from '@/Components/Forms/Textarea.vue'
import CurrencyInput from '@/Components/Forms/CurrencyInput.vue'
import NumberInput from '@/Components/Forms/NumberInput.vue'
import Switch from '@/Components/Forms/Switch.vue'
import Button from '@/Components/UI/Button.vue'

const form = useForm({
    name: '',
    description: '',
    price: 0,
    sku: '',
    stock: 0,
    active: true,
})

const submit = () => {
    form.post(route('cambo.products.store'))
}
</script>

<template>
    <AppLayout title="Create Product">
        <PageHeader
            title="Create Product"
            subtitle="Add a new product to your catalog"
            :back-link="route('cambo.products.index')"
        />

        <Card>
            <Form @submit="submit">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <FormGroup
                        label="Product Name"
                        :error="form.errors.name"
                        required
                    >
                        <Input
                            v-model="form.name"
                            placeholder="Enter product name"
                            :error="!!form.errors.name"
                        />
                    </FormGroup>

                    <FormGroup
                        label="SKU"
                        :error="form.errors.sku"
                        required
                    >
                        <Input
                            v-model="form.sku"
                            placeholder="e.g., PROD-001"
                            :error="!!form.errors.sku"
                        />
                    </FormGroup>

                    <FormGroup
                        label="Price"
                        :error="form.errors.price"
                        required
                    >
                        <CurrencyInput
                            v-model="form.price"
                            currency="USD"
                            :error="!!form.errors.price"
                        />
                    </FormGroup>

                    <FormGroup
                        label="Stock Quantity"
                        :error="form.errors.stock"
                        required
                    >
                        <NumberInput
                            v-model="form.stock"
                            :min="0"
                            :error="!!form.errors.stock"
                        />
                    </FormGroup>

                    <FormGroup
                        label="Description"
                        :error="form.errors.description"
                        class="md:col-span-2"
                    >
                        <Textarea
                            v-model="form.description"
                            placeholder="Enter product description"
                            rows="4"
                            :error="!!form.errors.description"
                        />
                    </FormGroup>

                    <FormGroup label="Active" class="md:col-span-2">
                        <Switch
                            v-model="form.active"
                            label="Product is available for sale"
                        />
                    </FormGroup>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <Button
                        :href="route('cambo.products.index')"
                        variant="secondary"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        variant="primary"
                        :loading="form.processing"
                    >
                        Create Product
                    </Button>
                </div>
            </Form>
        </Card>
    </AppLayout>
</template>
```

## Step 5: Customize Your CRUD

Now let's enhance the generated code with additional features.

### Adding Relationships

Update your model to include relationships:

```php
// app/Models/Product.php

use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'sku',
        'stock',
        'active',
        'category_id', // Add this
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

### Adding Validation Rules

Create a Form Request for more complex validation:

```php
// app/Http/Requests/StoreProductRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'sku' => 'required|string|max:100|unique:products|regex:/^[A-Z0-9-]+$/i',
            'stock' => 'required|integer|min:0|max:99999',
            'active' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.regex' => 'SKU can only contain letters, numbers, and dashes.',
        ];
    }
}
```

### Adding Filters

Add filter options to the index page:

```php
// In ProductController@index

$products = Product::query()
    ->when($request->search, function ($query, $search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
    })
    ->when($request->category, function ($query, $category) {
        $query->where('category_id', $category);
    })
    ->when($request->status !== null, function ($query) use ($request) {
        $query->where('active', $request->status === 'active');
    })
    ->when($request->stock, function ($query, $stock) {
        if ($stock === 'low') {
            $query->where('stock', '<=', 10);
        } elseif ($stock === 'out') {
            $query->where('stock', 0);
        }
    })
    ->paginate($request->per_page ?? 15)
    ->withQueryString();
```

### Adding Bulk Actions

Enable bulk operations in your DataTable:

```vue
<DataTable
    :data="products"
    :columns="columns"
    :filters="filters"
    selectable
    @selection-change="handleSelectionChange"
>
    <template #bulk-actions="{ selected }">
        <Button
            variant="secondary"
            size="sm"
            @click="bulkActivate(selected)"
        >
            Activate ({{ selected.length }})
        </Button>
        <Button
            variant="danger"
            size="sm"
            @click="bulkDelete(selected)"
        >
            Delete ({{ selected.length }})
        </Button>
    </template>
</DataTable>
```

## Step 6: Add to Sidebar Navigation

Update your sidebar to include the new Products section. In your layout or sidebar component:

```vue
const navigation = [
    {
        name: 'Dashboard',
        href: route('cambo.dashboard'),
        icon: HomeIcon,
    },
    {
        name: 'Products',
        href: route('cambo.products.index'),
        icon: CubeIcon,
    },
    // ... other items
]
```

## CRUD Generator Options

The `cambo:crud` command supports many options:

```bash
# Basic CRUD
php artisan cambo:crud Product --fields="name:string,price:decimal"

# With soft deletes
php artisan cambo:crud Product --fields="..." --soft-deletes

# With specific timestamps
php artisan cambo:crud Product --fields="..." --timestamps

# Without migration (model already exists)
php artisan cambo:crud Product --fields="..." --no-migration

# Without controller
php artisan cambo:crud Product --fields="..." --no-controller

# Without Vue pages
php artisan cambo:crud Product --fields="..." --no-views

# Force overwrite existing files
php artisan cambo:crud Product --fields="..." --force

# In a subdirectory
php artisan cambo:crud Admin/Product --fields="..."
```

### Field Types

| Type | Database | Example |
|------|----------|---------|
| `string` | VARCHAR(255) | `name:string` |
| `text` | TEXT | `description:text` |
| `integer` | INTEGER | `stock:integer` |
| `decimal` | DECIMAL(8,2) | `price:decimal` |
| `boolean` | BOOLEAN | `active:boolean` |
| `date` | DATE | `published_at:date` |
| `datetime` | DATETIME | `expires_at:datetime` |
| `json` | JSON | `metadata:json` |

## Customizing Templates

To customize the generated code templates:

```bash
php artisan vendor:publish --tag=cambo-admin-stubs
```

This publishes stubs to `stubs/cambo-admin/`:

```
stubs/cambo-admin/
├── controller.stub
├── model.stub
├── migration.stub
├── pages/
│   ├── index.stub
│   ├── create.stub
│   ├── edit.stub
│   └── show.stub
└── requests/
    ├── store.stub
    └── update.stub
```

Modify these templates to match your coding style.

## Next Steps

Congratulations! You've built your first CRUD with CamboAdmin. Here's what to explore next:

- **[Roles & Permissions](/guide/features/roles-permissions)** - Protect your CRUD with permissions
- **[Components](/components/)** - Explore all 150+ available components
- **[DataTable](/components/data/data-table)** - Advanced table features
- **[Import/Export](/guide/features/import-export)** - Add data import/export
- **[Activity Log](/guide/features/activity-log)** - Track changes to products
