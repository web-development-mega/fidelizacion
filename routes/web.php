<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\Admin\ClaimAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClaimController::class, 'landing'])->name('landing');
Route::get('/claim/{benefit}', [ClaimController::class, 'form'])->name('claim.form');
Route::post('/claim', [ClaimController::class, 'store'])->name('claim.store');

Route::get('/voucher/{code}', [ClaimController::class, 'showVoucher'])->name('voucher.show');
Route::get('/voucher/{code}/download', [ClaimController::class, 'downloadVoucher'])->name('voucher.download');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/claims', [ClaimAdminController::class, 'index'])->name('admin.claims.index');
    Route::get('/admin/claims/export', [ClaimAdminController::class, 'export'])->name('admin.claims.export');
});

require __DIR__.'/auth.php';
