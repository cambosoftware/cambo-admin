<?php

use CamboSoftware\CamboAdmin\Facades\Cambo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CamboAdmin Web Routes
|--------------------------------------------------------------------------
*/

$prefix = Cambo::routePrefix();
$middleware = Cambo::routeMiddleware();

Route::middleware($middleware)->prefix($prefix)->as('cambo.')->group(function () {

    // Dashboard
    if (Cambo::moduleEnabled('dashboard')) {
        Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/widget-types', [\App\Http\Controllers\DashboardController::class, 'widgetTypes'])->name('dashboard.widget-types');
        Route::post('/dashboard/widgets', [\App\Http\Controllers\DashboardController::class, 'addWidget'])->name('dashboard.widgets.add');
        Route::put('/dashboard/widgets/{widget}', [\App\Http\Controllers\DashboardController::class, 'updateWidget'])->name('dashboard.widgets.update');
        Route::put('/dashboard/widgets', [\App\Http\Controllers\DashboardController::class, 'updateWidgets'])->name('dashboard.widgets.update-all');
        Route::delete('/dashboard/widgets/{widget}', [\App\Http\Controllers\DashboardController::class, 'removeWidget'])->name('dashboard.widgets.remove');
        Route::post('/dashboard/reset', [\App\Http\Controllers\DashboardController::class, 'resetLayout'])->name('dashboard.reset');
    }

    // Users
    if (Cambo::moduleEnabled('users')) {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    }

    // Roles
    if (Cambo::moduleEnabled('roles')) {
        Route::resource('roles', \App\Http\Controllers\RoleController::class);
        Route::post('/roles/{role}/set-default', [\App\Http\Controllers\RoleController::class, 'setDefault'])->name('roles.set-default');
    }

    // Notifications
    if (Cambo::moduleEnabled('notifications')) {
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('notifications.recent');
        Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications/read', [\App\Http\Controllers\NotificationController::class, 'destroyRead'])->name('notifications.destroy-read');
    }

    // Activity Log
    if (Cambo::moduleEnabled('activity-log')) {
        Route::get('/activity-log', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('/activity-log/my-activities', [\App\Http\Controllers\ActivityLogController::class, 'myActivities'])->name('activity-log.my-activities');
        Route::get('/activity-log/{activity}', [\App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-log.show');
        Route::get('/activity-log/subject/{type}/{id}', [\App\Http\Controllers\ActivityLogController::class, 'forSubject'])->name('activity-log.subject');
        Route::delete('/activity-log/clear', [\App\Http\Controllers\ActivityLogController::class, 'clear'])->name('activity-log.clear');
    }

    // Media / File Manager
    if (Cambo::moduleEnabled('media')) {
        Route::get('/media', [\App\Http\Controllers\MediaController::class, 'index'])->name('media.index');
        Route::post('/media/upload', [\App\Http\Controllers\MediaController::class, 'upload'])->name('media.upload');
        Route::put('/media/{media}', [\App\Http\Controllers\MediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{media}', [\App\Http\Controllers\MediaController::class, 'destroy'])->name('media.destroy');
        Route::post('/media/bulk-delete', [\App\Http\Controllers\MediaController::class, 'bulkDestroy'])->name('media.bulk-destroy');
        Route::post('/media/move', [\App\Http\Controllers\MediaController::class, 'move'])->name('media.move');
        Route::get('/media/{media}/download', [\App\Http\Controllers\MediaController::class, 'download'])->name('media.download');
        Route::get('/media/folders/tree', [\App\Http\Controllers\MediaController::class, 'folderTree'])->name('media.folders.tree');
        Route::post('/media/folders', [\App\Http\Controllers\MediaController::class, 'createFolder'])->name('media.folders.create');
        Route::put('/media/folders/{folder}', [\App\Http\Controllers\MediaController::class, 'updateFolder'])->name('media.folders.update');
        Route::delete('/media/folders/{folder}', [\App\Http\Controllers\MediaController::class, 'destroyFolder'])->name('media.folders.destroy');
    }

    // Settings
    if (Cambo::moduleEnabled('settings')) {
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'store'])->name('settings.store');
        Route::delete('/settings/{setting}', [\App\Http\Controllers\SettingController::class, 'destroy'])->name('settings.destroy');
        Route::post('/settings/reset-group', [\App\Http\Controllers\SettingController::class, 'resetGroup'])->name('settings.reset-group');
    }

    // Import / Export
    if (Cambo::moduleEnabled('import-export')) {
        Route::get('/import-export', [\App\Http\Controllers\ImportExportController::class, 'index'])->name('import-export.index');
        Route::post('/import-export/export', [\App\Http\Controllers\ImportExportController::class, 'export'])->name('import-export.export');
        Route::get('/import-export/download', [\App\Http\Controllers\ImportExportController::class, 'download'])->name('import-export.download');
        Route::post('/import-export/upload', [\App\Http\Controllers\ImportExportController::class, 'upload'])->name('import-export.upload');
        Route::post('/import-export/import', [\App\Http\Controllers\ImportExportController::class, 'import'])->name('import-export.import');
        Route::get('/import-export/template', [\App\Http\Controllers\ImportExportController::class, 'template'])->name('import-export.template');
    }

    // Translations / i18n
    if (Cambo::moduleEnabled('i18n')) {
        Route::get('/translations', [\App\Http\Controllers\TranslationController::class, 'index'])->name('translations.index');
        Route::post('/translations', [\App\Http\Controllers\TranslationController::class, 'store'])->name('translations.store');
        Route::put('/translations', [\App\Http\Controllers\TranslationController::class, 'update'])->name('translations.update');
        Route::delete('/translations', [\App\Http\Controllers\TranslationController::class, 'destroy'])->name('translations.destroy');
        Route::get('/translations/export', [\App\Http\Controllers\TranslationController::class, 'export'])->name('translations.export');
        Route::post('/translations/import', [\App\Http\Controllers\TranslationController::class, 'import'])->name('translations.import');
        Route::post('/translations/create-locale', [\App\Http\Controllers\TranslationController::class, 'createLocale'])->name('translations.create-locale');
    }

    // Themes
    if (Cambo::moduleEnabled('themes')) {
        Route::get('/theme', [\App\Http\Controllers\ThemeController::class, 'index'])->name('theme.index');
        Route::post('/theme/switch', [\App\Http\Controllers\ThemeController::class, 'switch'])->name('theme.switch');
        Route::post('/theme/customize', [\App\Http\Controllers\ThemeController::class, 'customize'])->name('theme.customize');
        Route::delete('/theme', [\App\Http\Controllers\ThemeController::class, 'destroy'])->name('theme.destroy');
    }
});

// Locale switch (public route)
if (Cambo::moduleEnabled('i18n')) {
    Route::post('/locale/switch', [\App\Http\Controllers\TranslationController::class, 'switchLocale'])->name('locale.switch');
}
