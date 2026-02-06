<?php

namespace CamboSoftware\CamboAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed config(string $key, mixed $default = null)
 * @method static bool moduleEnabled(string $module)
 * @method static array enabledModules()
 * @method static string routePrefix()
 * @method static array routeMiddleware()
 * @method static string primaryColor()
 * @method static string appName()
 * @method static string darkMode()
 * @method static bool featureEnabled(string $feature)
 * @method static string userModel()
 *
 * @see \CamboSoftware\CamboAdmin\CamboAdmin
 */
class Cambo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cambo-admin';
    }
}
