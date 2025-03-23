<?php

namespace App\Http\Controllers\API;

use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function disponibles()
    {
        $vehiculos = Vehiculo::with('imagenes')
            ->where('disponible', true)
            ->get();

        return response()->json($vehiculos);
    }
}