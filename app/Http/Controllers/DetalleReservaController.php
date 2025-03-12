<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use Illuminate\Http\Request;

class DetalleReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $detallesReserva = DetalleReserva::with(['reserva', 'vehiculo'])->get();
            return response()->json($detallesReserva);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'fecha_entrega' => 'required|date',
                'fecha_retiro' => 'required|date',
                'monto_total' => 'required|numeric',
                'kilometraje_inicial' => 'required|numeric',
                'kilometraje_final' => 'nullable|numeric',
                'reserva_id' => 'required|exists:reservas,id',
                'vehiculo_id' => 'required|exists:vehiculos,id',
            ]);

            $detalleReserva = DetalleReserva::create($validatedData);

            return response()->json(["detalleReserva" => $detalleReserva, "message" => "Detalle de reserva creado con éxito"], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $detalleReserva = DetalleReserva::with(['reserva', 'vehiculo'])->findOrFail($id);
            return response()->json($detalleReserva);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $detalleReserva = DetalleReserva::findOrFail($id);
            $detalleReserva->fecha_entrega = $request->fecha_entrega;
            $detalleReserva->fecha_retiro = $request->fecha_retiro;
            $detalleReserva->monto_total = $request->monto_total;
            $detalleReserva->kilometraje_inicial = $request->kilometraje_inicial;
            $detalleReserva->kilometraje_final = $request->kilometraje_final;
            $detalleReserva->reserva_id = $request->reserva_id;
            $detalleReserva->vehiculo_id = $request->vehiculo_id;

            $detalleReserva->save();

            return response()->json(["detalleReserva" => $detalleReserva, "message" => "Detalle de reserva actualizado con éxito"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $detalleReserva = DetalleReserva::findOrFail($id);
            $detalleReserva->delete();
            return response()->json(["message" => "Detalle de reserva eliminado con éxito"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
