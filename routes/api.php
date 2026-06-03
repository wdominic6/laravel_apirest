<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;

// Esto crea automáticamente las 5 rutas del CRUD (GET, POST, PUT, DELETE)
Route::apiResource('products', ProductController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password-recovery', [AuthController::class, 'recoverPassword']);
Route::post('/google-login', [AuthController::class, 'googleLogin']); // Requisito de Google Auth

// Rutas Protegidas (Solo accesibles con Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Es recomendable mover tu CRUD de productos aquí dentro para que 
    // solo los usuarios logueados puedan crear, editar o borrar.
    Route::apiResource('products', ProductController::class);
});