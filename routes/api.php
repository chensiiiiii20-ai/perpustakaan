<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PerpustakaanController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\ActivityLogController;

/*
|--------------------------------------------------------------------------
| API AUTH (JWT)
|--------------------------------------------------------------------------
*/

// API routes ready

// Login (ambil token)
Route::post('/login', [AuthController::class, 'login']);

// Route yang membutuhkan JWT
Route::middleware(['auth:api'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH USER
    |--------------------------------------------------------------------------
    */

    // Info user login
    Route::get('/me', [AuthController::class, 'me']);

    // Refresh token
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | KATEGORI BUKU
    |--------------------------------------------------------------------------
    */

    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/{id}', [KategoriController::class, 'show']);
    Route::post('/kategori', [KategoriController::class, 'store']);
    Route::put('/kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | PERPUSTAKAAN / BUKU
    |--------------------------------------------------------------------------
    */

    Route::get('/perpustakaan', [PerpustakaanController::class, 'index']);
    Route::get('/perpustakaan/{id}', [PerpustakaanController::class, 'show']);
    Route::post('/perpustakaan', [PerpustakaanController::class, 'store']);
    Route::put('/perpustakaan/{id}', [PerpustakaanController::class, 'update']);
    Route::patch('/perpustakaan/{id}', [PerpustakaanController::class, 'updatePartial']);
    Route::delete('/perpustakaan/{id}', [PerpustakaanController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | PEMINJAMAN BUKU
    |--------------------------------------------------------------------------
    */

    Route::get('/peminjaman', [PeminjamanController::class, 'index']);
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update']);
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG (READ ONLY)
    |--------------------------------------------------------------------------
    */

    Route::get('/logs', [ActivityLogController::class, 'index']);
    Route::get('/logs/{id}', [ActivityLogController::class, 'show']);
    Route::get('/my-logs', [ActivityLogController::class, 'myLogs']);

});