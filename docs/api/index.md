# Reference API

Documentation complete de l'API PHP de CamboAdmin.

## Facade & Services

### Cambo Facade

La [facade Cambo](/api/facade) fournit un acces simplifie a la configuration et aux fonctionnalites principales.

```php
use Cambo;

$appName = Cambo::appName();
$isEnabled = Cambo::moduleEnabled('notifications');
```

### ThemeService

Le [ThemeService](/api/theme-service) gere les themes et le mode sombre.

```php
use CamboSoftware\CamboAdmin\Services\ThemeService;

$themeService = app(ThemeService::class);
$themes = $themeService->getThemes();
```

### TranslationService

Le [TranslationService](/api/translation-service) gere l'internationalisation.

```php
use CamboSoftware\CamboAdmin\Services\TranslationService;

$translationService = app(TranslationService::class);
$locales = $translationService->getSupportedLocales();
```

### ImportExportService

Le [ImportExportService](/api/import-export-service) gere l'import et l'export de donnees.

```php
use CamboSoftware\CamboAdmin\Services\ImportExportService;

$service = app(ImportExportService::class);
$path = $service->exportToCsv($data, $columns);
```

## Modeles

### Role

Le modele [Role](/api/role) represente un role utilisateur.

```php
use CamboSoftware\CamboAdmin\Models\Role;

$role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
$role->givePermissions(['posts.edit', 'posts.create']);
```

### Permission

Le modele [Permission](/api/permission) represente une permission.

```php
use CamboSoftware\CamboAdmin\Models\Permission;

$permission = Permission::create([
    'name' => 'Edit Posts',
    'slug' => 'posts.edit',
    'group' => 'posts',
]);
```

### Setting

Le modele [Setting](/api/setting) gere les parametres dynamiques.

```php
use CamboSoftware\CamboAdmin\Models\Setting;

Setting::set('site_name', 'Mon Site');
$value = Setting::get('site_name', 'Default');
```

## Traits

### HasRoles

Le trait [HasRoles](/api/has-roles) ajoute la gestion des roles et permissions a un modele.

```php
use CamboSoftware\CamboAdmin\Models\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}

$user->assignRole('editor');
$user->hasPermission('posts.edit');
```

### LogsActivity

Le trait LogsActivity ajoute le journal d'activite a un modele.

```php
use CamboSoftware\CamboAdmin\Models\Traits\LogsActivity;

class Post extends Model
{
    use LogsActivity;
}
```

## Middleware

### CheckRole

Le [middleware CheckRole](/api/check-role) protege les routes par role.

```php
Route::middleware('role:admin')->group(function () {
    // Routes admin
});
```

### CheckPermission

Le [middleware CheckPermission](/api/check-permission) protege les routes par permission.

```php
Route::middleware('permission:posts.edit')->group(function () {
    // Routes avec permission
});
```

### SetLocale

Le middleware SetLocale gere la langue de l'application.

```php
Route::middleware('locale')->group(function () {
    // Routes avec langue automatique
});
```

## Commandes Artisan

| Commande | Description |
|----------|-------------|
| `cambo:install` | Installation interactive |
| `cambo:crud` | Genere un CRUD complet |
| `cambo:page` | Genere une page Vue |
| `cambo:component` | Genere un composant Vue |
| `cambo:add` | Ajoute un module |

## Events

CamboAdmin dispatche plusieurs events :

| Event | Description |
|-------|-------------|
| `RoleAssigned` | Un role a ete assigne |
| `RoleRemoved` | Un role a ete retire |
| `PermissionGranted` | Une permission a ete accordee |
| `SettingChanged` | Un parametre a change |
| `UserLoggedIn` | Connexion utilisateur |
