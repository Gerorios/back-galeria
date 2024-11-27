<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\NovedadController;


// Rutas de autenticación para el administrador
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');

// Grupo de rutas protegidas para administradores
Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::prefix('locales')->group(function () {
        Route::get('/', [LocalController::class, 'index']);           // Obtener todos los locales
        Route::post('/', [LocalController::class, 'store']);          // Crear un nuevo local
        Route::get('/{id}', [LocalController::class, 'show']);        // Mostrar un local específico
        Route::put('/{id}', [LocalController::class, 'update']);      // Actualizar un local existente
        Route::delete('/{id}', [LocalController::class, 'destroy']);  // Eliminar un local
    });

    // Rutas para manejar imágenes de locales
    Route::post('/locales/{localId}/imagenes', [LocalController::class, 'agregarImagen']);
    Route::get('/locales/{localId}/imagenes', [LocalController::class, 'listarImagenes']);
    //Rutas Para ultimas novedades
    Route::get('/novedades', [NovedadController::class, 'index']);
    Route::post('/novedades', [NovedadController::class, 'store']);
    Route::put('/novedades/{id}', [NovedadController::class, 'update']);
    Route::delete('/novedades/{id}', [NovedadController::class, 'destroy']);
});
//RUTA PARA OBTENER TODO LOS LOCALES Y LO PUEDAN VER LOS USUARIOS
Route::prefix('locales')->group(function () {
    Route::get('/', [LocalController::class, 'index']);
    Route::get('/{id}', [LocalController::class, 'show']);
    Route::get('/locales/{localId}/imagenes', [LocalController::class, 'listarImagenes']);
});

Route::get('/novedades', [NovedadController::class, 'index']);
