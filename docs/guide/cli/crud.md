# cambo:crud Command

The `cambo:crud` command generates a complete CRUD (Create, Read, Update, Delete) module including migration, model, controller, and Vue pages.

## Basic Usage

```bash
php artisan cambo:crud Product
```

This creates a complete CRUD for a "Product" model with:
- Database migration
- Eloquent model
- Resource controller
- Vue pages (Index, Create, Edit, Show)
- Route definitions

## Command Signature

```bash
php artisan cambo:crud
    {name : The name of the model (e.g., Product)}
    {--fields= : Fields definition (e.g., "name:string,price:decimal,active:boolean")}
    {--soft-deletes : Add soft deletes}
    {--api : Generate API routes}
    {--force : Overwrite existing files}
```

## Options

### `name` (Required)

The model name in singular PascalCase format.

```bash
# Correct
php artisan cambo:crud Product
php artisan cambo:crud BlogPost
php artisan cambo:crud UserProfile

# The command will convert to proper format
php artisan cambo:crud product     # Becomes: Product
php artisan cambo:crud blog_post   # Becomes: BlogPost
```

### `--fields`

Define model fields with their types. Format: `field_name:type,field_name:type`

```bash
php artisan cambo:crud Product --fields="name:string,price:decimal,quantity:integer,active:boolean"
```

### `--soft-deletes`

Add soft deletes to the model and migration.

```bash
php artisan cambo:crud Product --soft-deletes
```

### `--api`

Generate API routes in addition to web routes (future feature).

```bash
php artisan cambo:crud Product --api
```

### `--force`

Overwrite existing files without confirmation.

```bash
php artisan cambo:crud Product --force
```

## Supported Field Types

### Basic Types

| Type | Migration Method | PHP Cast | Example |
|------|------------------|----------|---------|
| `string` | `$table->string()` | - | `name:string` |
| `text` | `$table->text()` | - | `description:text` |
| `integer` or `int` | `$table->integer()` | `integer` | `quantity:integer` |
| `bigint` | `$table->bigInteger()` | `integer` | `views:bigint` |
| `decimal` | `$table->decimal(10,2)` | `float` | `price:decimal` |
| `float` | `$table->float()` | `float` | `rating:float` |
| `boolean` or `bool` | `$table->boolean()->default(false)` | `boolean` | `active:boolean` |

### Date and Time Types

| Type | Migration Method | PHP Cast | Example |
|------|------------------|----------|---------|
| `date` | `$table->date()` | `date` | `birth_date:date` |
| `datetime` | `$table->dateTime()` | `datetime` | `published_at:datetime` |
| `timestamp` | `$table->timestamp()` | `datetime` | `last_login:timestamp` |

### Special Types

| Type | Migration Method | PHP Cast | Example |
|------|------------------|----------|---------|
| `json` | `$table->json()` | `array` | `metadata:json` |

### Nullable Fields

Append `?` to make a field nullable:

```bash
php artisan cambo:crud Product --fields="name:string,description:text?,price:decimal"
```

This generates:

```php
$table->string('name');
$table->text('description')->nullable();
$table->decimal('price', 10, 2);
```

## Examples

### E-commerce Product

```bash
php artisan cambo:crud Product --fields="name:string,slug:string,description:text?,price:decimal,quantity:integer,active:boolean,featured:boolean,metadata:json?" --soft-deletes
```

**Generated Migration:**

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('quantity');
    $table->boolean('active')->default(false);
    $table->boolean('featured')->default(false);
    $table->json('metadata')->nullable();
    $table->softDeletes();
    $table->timestamps();
});
```

**Generated Model:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'price', 'quantity', 'active', 'featured', 'metadata'];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'active' => 'boolean',
        'featured' => 'boolean',
        'metadata' => 'array',
    ];
}
```

### Blog Post

```bash
php artisan cambo:crud BlogPost --fields="title:string,slug:string,content:text,excerpt:text?,published_at:datetime?,views:integer" --soft-deletes
```

### Task Management

```bash
php artisan cambo:crud Task --fields="title:string,description:text?,priority:integer,due_date:date?,completed:boolean"
```

### Customer Record

```bash
php artisan cambo:crud Customer --fields="name:string,email:string,phone:string?,company:string?,notes:text?,metadata:json?"
```

## Generated Files

For a `Product` model, the following files are created:

### Migration

**Location:** `database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Your fields here
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Model

**Location:** `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'active'];

    protected $casts = [
        'price' => 'float',
        'active' => 'boolean',
    ];
}
```

### Controller

**Location:** `app/Http/Controllers/ProductController.php`

The controller includes all resourceful methods:
- `index()` - List all products with search and sorting
- `create()` - Show create form
- `store()` - Save new product
- `show()` - Show single product
- `edit()` - Show edit form
- `update()` - Update product
- `destroy()` - Delete product

### Vue Pages

**Location:** `resources/js/Pages/Products/`

| File | Purpose |
|------|---------|
| `Index.vue` | List view with search, pagination, and actions |
| `Create.vue` | Create form |
| `Edit.vue` | Edit form |
| `Show.vue` | Detail view |

### Routes

Added to `routes/web.php`:

```php
// Product CRUD
Route::resource('products', App\Http\Controllers\ProductController::class);
```

## Customizing Generated Files

After generation, you should customize the following:

### 1. Controller Validation

Add validation rules in `store()` and `update()` methods:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'active' => 'boolean',
    ]);

    Product::create($validated);

    return redirect()
        ->route('products.index')
        ->with('success', 'Product created successfully.');
}
```

### 2. Controller Search

Customize the search query in `index()`:

```php
if ($search = $request->get('search')) {
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
    });
}
```

### 3. Vue Form Fields

Update the form fields in `Create.vue` and `Edit.vue`:

```vue
<FormGroup label="Name" :error="form.errors.name">
    <Input v-model="form.name" placeholder="Enter product name" />
</FormGroup>

<FormGroup label="Price" :error="form.errors.price">
    <Input v-model="form.price" type="number" step="0.01" placeholder="0.00" />
</FormGroup>

<FormGroup label="Active">
    <Toggle v-model="form.active" />
</FormGroup>
```

### 4. Table Columns

Update table columns in `Index.vue`:

```vue
<TableHead>
    <TableCell header>ID</TableCell>
    <TableCell header>Name</TableCell>
    <TableCell header>Price</TableCell>
    <TableCell header>Status</TableCell>
    <TableCell header class="text-right">Actions</TableCell>
</TableHead>
<TableBody>
    <TableRow v-for="product in products.data" :key="product.id">
        <TableCell>{{ product.id }}</TableCell>
        <TableCell>{{ product.name }}</TableCell>
        <TableCell>${{ product.price.toFixed(2) }}</TableCell>
        <TableCell>
            <Badge :variant="product.active ? 'success' : 'secondary'">
                {{ product.active ? 'Active' : 'Inactive' }}
            </Badge>
        </TableCell>
        <TableCell class="text-right">
            <!-- Actions -->
        </TableCell>
    </TableRow>
</TableBody>
```

## Post-Generation Steps

After generating a CRUD:

```bash
# 1. Review the migration
cat database/migrations/*_create_products_table.php

# 2. Run the migration
php artisan migrate

# 3. Build assets
npm run dev

# 4. Visit the CRUD pages
# http://localhost:8000/products
```

## Tips and Best Practices

### Use Meaningful Field Names

```bash
# Good
--fields="first_name:string,last_name:string,email:string"

# Avoid
--fields="fn:string,ln:string,e:string"
```

### Group Related CRUDs

For related models, generate them together:

```bash
php artisan cambo:crud Category --fields="name:string,slug:string,description:text?"
php artisan cambo:crud Product --fields="name:string,category_id:bigint,price:decimal"
```

### Add Relationships Manually

After generation, add relationships to your models:

```php
// Category.php
public function products()
{
    return $this->hasMany(Product::class);
}

// Product.php
public function category()
{
    return $this->belongsTo(Category::class);
}
```

## See Also

- [cambo:page](./page.md) - Generate individual pages
- [cambo:component](./component.md) - Generate Vue components
- [cambo:install](./install.md) - Initial installation
