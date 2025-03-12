<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response()->json(Marca::all());
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
            // primero verificamos que el nombre de marca no exista
            $existeMarca = Marca::where("nombre", $request->nombre)->first();
            if($existeMarca){
              return response()->json(["message"=>"esta Marca ya esta registrada en la base de datos"],409);
            }else{    
                $marca = Marca::create($request->all());
                return response()->json(["marca"=>$marca,"message"=>"Marca registrado con exito...!"],201);
            }
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
