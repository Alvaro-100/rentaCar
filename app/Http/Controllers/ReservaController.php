<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response()->json(Reserva::with('cliente')->get());
         }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage(),500]);
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
            $request->validate([
                'estado'     => 'required|in:pendiente,confirmada,finalizada,cancelada',
                'cliente.id' => 'required|exists:clientes,id'
            ]);
            
            $estado = strtolower($request->estado);
            $cliente_id = $request->input('cliente.id');
    
            $reserva = Reserva::create([
                'estado' => $estado,
                'cliente_id' => $cliente_id
            ]);

            return response()->json([
                'message' => 'Reserva creada exitosamente',
                'reserva' => $reserva
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            return response()->json(Reserva::with('cliente')->findOrFail($id));
         }catch(\Exception $e){
             return response()->json(['error'=>$e->getMessage()], 409);
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
    public function update(Request $request, $id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
    
            $request->validate([
                'estado' => 'required|in:pendiente,confirmada,cancelada',
            ]);
    
            $reserva->estado = $request->estado;
            $reserva->save();
    
            return response()->json(['mensaje' => 'Estado actualizado', 'reserva' => $reserva]);
    
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
