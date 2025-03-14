<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Ruta para la página de inicio
Route::get('/', function () {
    return Inertia::render('CatalogoVehiculos');
})->name('CatalogoVehiculos');

// Ruta para el dashboard general
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por middleware de autenticación y roles
    Route::get('/admin', function () {
        return Inertia::render('AdminDashboard');
    })->name('admin.dashboard');

    Route::get('/Cliente', function () {
        return Inertia::render('ClientDashboard');
    })->name('Client.dashboard');

// Rutas protegidas por middleware de autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
