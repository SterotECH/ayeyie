<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Admin;
use App\Models\AuditLog;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Volt::route('/product/{product}', 'welcome.products.show')
    ->name('welcome.products.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Route::prefix('/admin')->group(function () {
        Route::get('/products', Admin\Product\Index::class)
            ->name('admin.products.index');
        Route::get('/products/create', Admin\Product\Create::class)
            ->name('admin.products.create');
        Route::get('/products/{product}/edit', Admin\Product\Edit::class)
            ->name('admin.products.edit');
        Route::get('/products/{product}', Admin\Product\Show::class)
            ->name('admin.products.show');

        Route::get('/users', Admin\Users\Index::class)
            ->name('admin.users.index');
        Route::get('/users/create', Admin\Users\Create::class)
            ->name('admin.users.create');
        Route::get('/users/{user}/edit', Admin\Users\Edit::class)
            ->name('admin.users.edit');
        Route::get('/users/{user}', Admin\Users\Show::class)
            ->name('admin.users.show');

        Route::get('/suspicious_activities', Admin\SuspiciousActivity\Index::class)
            ->name('admin.suspicious_activities.index');
        Route::get('/suspicious_activities/{activity}', Admin\SuspiciousActivity\Show::class)
            ->name('admin.suspicious_activities.show');

        Route::get('/stock_alerts', Admin\StockAlert\Index::class)
            ->name('admin.stock_alerts.index');
        Route::get('/stock_alerts/{alert}', Admin\StockAlert\Show::class)
            ->name('admin.stock_alerts.show');

        Route::get('/audit_logs', Admin\AuditLog\Index::class)
            ->name('admin.audit_logs.index');
        Route::get('/audit_logs/{log}', Admin\AuditLog\Show::class)
            ->name('admin.audit_logs.show');
    });
});

require __DIR__ . '/auth.php';
