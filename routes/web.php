<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\Admin\ClaimAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Públicas (landing, formulario, voucher)
|--------------------------------------------------------------------------
*/
Route::controller(ClaimController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');

    // antes: /claim/{benefit}
    Route::get('/claim/{benefit?}', 'form')->name('claim.form');

    // agrega throttling básico para el POST
    Route::post('/claim', 'store')
        ->middleware('throttle:6,1')   // 6 intentos por minuto (ajústalo si quieres)
        ->name('claim.store');

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
| Perfil (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

if (app()->environment('local')) {
    Route::get('/__logtest', function () {
        Log::debug('Log test @ '.now());
        return 'ok';
    });
}


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (app()->environment('local')) {
    Route::get('/__dbcheck', function () {
        return response()->json([
            'env'          => app()->environment(),
            'connection'   => config('database.default'),
            'driver'       => DB::connection()->getDriverName(),
            'database'     => DB::connection()->getDatabaseName(),
            'claims_table' => Schema::hasTable('claims'),
            'claims_count' => \App\Models\Claim::count(),
            'first_claim'  => \App\Models\Claim::select('id','nombre','cedula','telefono','email','benefit')->first(),
        ]);
    });
}




Route::get('/__claims_pragma', function () {
    return response()->json([
        'columns' => DB::select('PRAGMA table_info(claims)'),
        'indexes' => DB::select('PRAGMA index_list(claims)'),
    ]);
});
