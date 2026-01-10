<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PerpustakaanController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API AUTH (JWT)
|--------------------------------------------------------------------------
*/

// Login (ambil token)
Route::post('/login', [AuthController::class, 'login']);

// Route yang butuh JWT
Route::middleware('auth:api')->group(function () {

    // Info user login
    Route::get('/me', [AuthController::class, 'me']);

    // Refresh token
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | API Perpustakaan (CRUD)
    |--------------------------------------------------------------------------
    */

    // GET semua data
    Route::get('/perpustakaan', [PerpustakaanController::class, 'index']);

    // GET detail data by ID
    Route::get('/perpustakaan/{id}', [PerpustakaanController::class, 'show']);

    // POST tambah data baru
    Route::post('/perpustakaan', [PerpustakaanController::class, 'store']);

    // PUT update semua data
    Route::put('/perpustakaan/{id}', [PerpustakaanController::class, 'update']);

    // PATCH update sebagian data
    Route::patch('/perpustakaan/{id}', [PerpustakaanController::class, 'updatePartial']);

    // DELETE hapus data
    Route::delete('/perpustakaan/{id}', [PerpustakaanController::class, 'destroy']);
});
