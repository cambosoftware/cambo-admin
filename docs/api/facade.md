# Facade Cambo

La facade `Cambo` fournit un acces simplifie aux fonctionnalites principales de CamboAdmin.

## Utilisation

```php
use CamboSoftware\CamboAdmin\Facades\Cambo;

// ou
use Cambo;
```

## Methodes disponibles

### Configuration

#### `config(string $key, mixed $default = null): mixed`

Recupere une valeur de configuration.

```php
$appName = Cambo::config('appearance.name');
$primaryColor = Cambo::config('appearance.primary_color', '#3B82F6');
```

### Modules

#### `moduleEnabled(string $module): bool`

Verifie si un module est active.

```php
if (Cambo::moduleEnabled('notifications')) {
    // Le module notifications est actif
}
```

#### `enabledModules(): array`

Retourne la liste des modules actives.

```php
$modules = Cambo::enabledModules();
// ['auth', 'users', 'roles', 'notifications', ...]
```

### Routes

#### `routePrefix(): string`

Retourne le prefixe des routes.

```php
$prefix = Cambo::routePrefix();
// 'admin'
```

#### `routeMiddleware(): array`

Retourne les middleware des routes.

```php
$middleware = Cambo::routeMiddleware();
// ['web', 'auth', 'verified']
```

### Apparence

#### `appName(): string`

Retourne le nom de l'application.

```php
$name = Cambo::appName();
```

#### `primaryColor(): string`

Retourne la couleur primaire.

```php
$color = Cambo::primaryColor();
// '#3B82F6'
```

#### `darkMode(): string`

Retourne le mode d'affichage.

```php
$mode = Cambo::darkMode();
// 'system', 'light', ou 'dark'
```

### Fonctionnalites

#### `featureEnabled(string $feature): bool`

Verifie si une fonctionnalite est activee.

```php
if (Cambo::featureEnabled('two_factor')) {
    // L'authentification 2FA est activee
}

if (Cambo::featureEnabled('registration')) {
    // L'inscription est activee
}
```

### Modeles

#### `userModel(): string`

Retourne la classe du modele User.

```php
$userClass = Cambo::userModel();
// 'App\Models\User'
```

## Exemple complet

```php
use Cambo;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'appName' => Cambo::appName(),
            'primaryColor' => Cambo::primaryColor(),
            'darkMode' => Cambo::darkMode(),
            'modules' => Cambo::enabledModules(),
            'features' => [
                'twoFactor' => Cambo::featureEnabled('two_factor'),
                'registration' => Cambo::featureEnabled('registration'),
            ],
        ];

        return Inertia::render('Dashboard', $data);
    }
}
```

## Acces direct au service

Vous pouvez aussi injecter le service directement :

```php
use CamboSoftware\CamboAdmin\CamboAdmin;

class MyController extends Controller
{
    public function __construct(
        protected CamboAdmin $cambo
    ) {}

    public function index()
    {
        $appName = $this->cambo->appName();
    }
}
```

## Helper global

Un helper global est aussi disponible :

```php
// Dans un controller ou service
$appName = cambo()->appName();
$isModuleEnabled = cambo()->moduleEnabled('notifications');
```
