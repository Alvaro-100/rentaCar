<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\DetalleReservaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ImagenController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('reservas', ReservaController::class);
Route::apiResource('detalles_reservas', DetalleReservaController::class);
Route::apiResource('marcas', MarcaController::class);
Route::apiResource('imagenes', ImagenController::class);
Route::apiResource('vehiculos', VehiculoController::class);
