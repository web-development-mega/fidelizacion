<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\Admin\ClaimAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Públicas (landing, formulario, voucher)
|--------------------------------------------------------------------------
*/
Route::controller(ClaimController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
    Route::get('/claim/{benefit}', 'form')->name('claim.form');
    Route::post('/claim', 'store')->name('claim.store');

    Route::get('/voucher/{code}', 'showVoucher')->name('voucher.show');
    Route::get('/voucher/{code}/download', 'downloadVoucher')->name('voucher.download');
});

/*
|--------------------------------------------------------------------------
| Admin (protegidas)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/claims', [ClaimAdminController::class, 'index'])->name('claims.index');
        Route::get('/claims/export', [ClaimAdminController::class, 'export'])->name('claims.export');
        Route::get('/claims/{code}', [ClaimAdminController::class, 'show'])->name('claims.show');
    });

/*
|--------------------------------------------------------------------------
| Dashboard (alias para Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => redirect()->route('admin.claims.index'))
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Perfil (Breeze) - evita error "Route [profile.edit] not defined"
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
