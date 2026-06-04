<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;

// Rutas Públicas (sin token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password-recovery', [AuthController::class, 'recoverPassword']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);

// Rutas Protegidas (requieren token de Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ruta para obtener el usuario actual
    Route::get('/user', function (Request $request) {
        return response()->json($request->user(), 200);
    });

    // CRUD completo de productos (solo usuarios autenticados)
    Route::apiResource('products', ProductController::class);
});