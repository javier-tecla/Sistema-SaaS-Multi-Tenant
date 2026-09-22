<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

// routes/web.php, api.php or any other central route files you have

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // your actual routes
        Route::get('/', function () {
            return view('welcome');
        });

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        require __DIR__.'/auth.php';

        Route::get('/admin/tenants', [TenantController::class, 'index'])->name('admin.tenants.index')->middleware('auth');
        Route::get('/admin/tenants/create', [TenantController::class, 'create'])->name('admin.tenants.create')->middleware('auth');
        Route::post('/admin/tenants', [TenantController::class, 'store'])->name('admin.tenants.store')->middleware('auth');
        Route::get('/admin/tenants/check-subdomain', [TenantController::class, 'checkSubdomain'])->name('admin.tenants.check-subdomain')->middleware('auth');
        Route::get('/admin/tenants/{tenant}/edit', [TenantController::class, 'edit'])->name('admin.tenants.edit')->middleware('auth');
        Route::put('/admin/tenants/{tenant}', [TenantController::class, 'update'])->name('admin.tenants.update')->middleware('auth');
        Route::delete('/admin/tenants/{tenant}', [TenantController::class, 'destroy'])->name('admin.tenants.destroy')->middleware('auth');
        

    });
}
