# Quick Start

This guide will help you create your first CRUD in under 5 minutes.

## 1. Create a New CRUD

Use the `cambo:crud` command to generate a complete CRUD:

```bash
php artisan cambo:crud Product --fields="name:string,description:text,price:decimal,active:boolean"
```

This command generates:
- The `App\Models\Product` model
- The `App\Http\Controllers\ProductController` controller
- The migration for the `products` table
- Vue pages (Index, Create, Edit, Show)
- Routes in `web.php`

## 2. Run the Migration

```bash
php artisan migrate
```

## 3. Access the CRUD

Visit `http://localhost:8000/admin/products` to see your CRUD in action.

## Generated Structure

### Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];
}
```

### Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, fn($q, $search) =>
                $q->where('name', 'like', "%{$search}%")
            )
            ->paginate(15);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    // ... other methods
}
```

### Vue Page (Index)

```vue
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import DataTable from '@/Components/DataTable/DataTable.vue'
import Button from '@/Components/UI/Button.vue'

const props = defineProps({
    products: Object,
    filters: Object,
})

const columns = [
    { key: 'name', label: 'Name', sortable: true },
    { key: 'price', label: 'Price', sortable: true },
    { key: 'active', label: 'Active', type: 'boolean' },
    { key: 'actions', label: 'Actions' },
]
</script>

<template>
    <AppLayout title="Products">
        <DataTable
            :data="products"
            :columns="columns"
            :filters="filters"
        >
            <template #actions="{ row }">
                <Button
                    variant="ghost"
                    size="sm"
                    @click="router.visit(route('products.edit', row.id))"
                >
                    Edit
                </Button>
            </template>
        </DataTable>
    </AppLayout>
</template>
```

## CRUD Generator Options

```bash
# With soft deletes
php artisan cambo:crud Product --soft-deletes

# With custom timestamps
php artisan cambo:crud Product --timestamps

# Without migration
php artisan cambo:crud Product --no-migration

# Without controller
php artisan cambo:crud Product --no-controller

# Without Vue pages
php artisan cambo:crud Product --no-views

# Force overwrite
php artisan cambo:crud Product --force
```

## Customize Templates

Publish the stubs to customize them:

```bash
php artisan vendor:publish --tag=cambo-admin-stubs
```

The stubs are now in `stubs/cambo-admin/` and can be modified.

## Next Steps

- [Configure roles and permissions](/guide/features/roles-permissions)
- [Add notifications](/guide/features/notifications)
- [Customize the dashboard](/guide/features/dashboard)
