<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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
        Route::get('/products', App\Livewire\Admin\Product\Index::class)
            ->name('admin.products.index');
        Route::get('/products/create', App\Livewire\Admin\Product\Create::class)
            ->name('admin.products.create');
        Route::get('/products/{product}/edit', App\Livewire\Admin\Product\Edit::class)
            ->name('admin.products.edit');
        Route::get('/products/{product}', App\Livewire\Admin\Product\Show::class)
            ->name('admin.products.show');
        Route::get('/users', App\Livewire\Admin\Users\Index::class)
            ->name('admin.users.index');
        Route::get('/users/create', App\Livewire\Admin\Users\Create::class)
            ->name('admin.users.create');
        Route::get('/users/{user}/edit', App\Livewire\Admin\Users\Edit::class)
            ->name('admin.users.edit');
        Route::get('/users/{user}', App\Livewire\Admin\Users\Show::class)
            ->name('admin.users.show');
        // Route::get('/transactions', App\Livewire\Admin\Transactions\Index
    });
});

require __DIR__ . '/auth.php';
