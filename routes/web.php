<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use function Laravel\Prompts\form;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/',function(){
    return Inertia::render('CatalogoVehiculos');
})->name('CatalogoVehiculos');

Route::get('/formCliente',function(){
    return Inertia::render('formCliente');
})->middleware(['auth', 'verified'])->name('formulario');

// Route::get('/ClienteVehiculo',function(){
//     return Inertia::render('interfazcliente/ClienteVehiculo');
// })->name('clienteVehiculo');

Route::get('/Marcas', function () {
    return Inertia::render('interfazadmin/Marcas');
})->middleware(['auth', 'verified'])->name('Marcas');

Route::get('/Vehiculo', function () {
    return Inertia::render('interfazadmin/Vehiculo');
})->middleware(['auth', 'verified'])->name('Vehiculo');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post('formCliente',[ClienteController::class, 'informacionCliente'])->name('formCliente');

Route::post('/Cliente', [ClienteController::class, 'store']);

require __DIR__.'/auth.php';
