<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response()->json(Cliente::all());
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
        try{
            $cliente = Cliente::create($request->all());

        return response()->json([
            'message' => 'Cliente creado correctamente',
            'cliente' => $cliente
        ], 201);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage(),409]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            return response()->json(Cliente::findOrFail($id));
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
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'telefono' => 'required|numeric|digits_between:1,10',
                'direccion' => 'required|string|max:255',
            ]);

            // Verificar si ya existe un cliente con el mismo nombre
            $existeCliente = Cliente::where("nombre", $request->nombre)->first();
            if ($existeCliente && $existeCliente->id != $id) {
                return response()->json(["message" => "Ya existe este Usuario...."], 409);
            } else {
                // Buscar el cliente por ID y actualizar los datos
                $cliente = Cliente::findOrFail($id);
                $cliente->nombre = $validatedData['nombre'];
                $cliente->apellido = $validatedData['apellido'];
                $cliente->telefono = $validatedData['telefono'];
                $cliente->direccion = $validatedData['direccion'];

                if ($cliente->save()) {
                    return response()->json(["cliente" => $cliente, "message" => "Información del cliente actualizada"], 202);
                } else {
                    return response()->json(["message" => "Ocurrió un error al actualizar el registro"], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $cliente = Cliente::findOrFail($id);
            if($cliente->delete() > 0){
                return response()->json(["message"=>"El cliente hasido eliminado"],200);
            }else{
               return response()->json(["message"=>"Imposible eliminar el cliente..!"],500);
            } 
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
