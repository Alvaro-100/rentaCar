<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Imagen;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vehiculos = Vehiculo::with('imagenes','marcas')->get();
            return response()->json($vehiculos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            
            // Decodificar los datos del vehículo desde el request
            $vehiculoS = json_decode($request->input("vehiculo"), true);
        
            // Validar si ya existe un vehículo con los mismos datos
            $vali = Vehiculo::where("matricula", $vehiculoS["matricula"] ?? null)
                ->where("descripcion", $vehiculoS["descripcion"] ?? null)
                ->where("marca_id", $vehiculoS["marca"]["id"] ?? null)
                ->first();
        
            if ($vali) {
                return response()->json(["message" => "Ya existe un registro con estos datos"], 409);
            }
        
            // Crear una nueva instancia de Vehiculo y asignar valores
            $vehiculo = new Vehiculo();
            $vehiculo->matricula = $vehiculoS["matricula"];
            $vehiculo->modelo = $vehiculoS["modelo"];
            $vehiculo->precio = $vehiculoS["precio"];
            $vehiculo->descripcion = $vehiculoS["descripcion"];
            $vehiculo->aire_acondicionado = $vehiculoS["aire_acondicionado"];
            $vehiculo->conexion_bluetooth = $vehiculoS["conexion_bluetooth"];
            $vehiculo->disponibilidad = $vehiculoS["disponibilidad"];
            $vehiculo->marca_id = $vehiculoS["marca"]["id"];
            $vehiculo->save(); // Guardar en la base de datos
        
            // Verificar si la petición trae imágenes
            if ($request->hasFile('imagenes')) {
        
                // Recorrer la colección de imágenes
                foreach ($request->file('imagenes') as $img) {
                    // Generar un nombre único para la imagen
                    $imageName = uniqid() . '_' . $img->getClientOriginalName();
        
                    // Mover la imagen a la carpeta de almacenamiento
                    $img->move(public_path('images/vehiculo'), $imageName);

        
                    // Crear instancia de Imagen y guardar el registro
                    $image = new Imagen();
                    $image->nombre = $imageName;
                    $image->vehiculo_id = $vehiculo->id;
                    $image->save();
                }
            }
            
            // Obtener el vehículo recién creado con más detalles si es necesario
            $vehiculoP = $this->show($vehiculo->id);
            
            return response()->json([
                "vehiculo" => $vehiculoP,
                "message" => "El vehículo se ha registrado con éxito"
            ], 201);
            
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
            $vehiculo = Vehiculo::with('imagenes','marcas')->findOrFail($id);
            return response()->json($vehiculo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Este método no es necesario para una API RESTful
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    try {

        $validatedData = $request->validate([
            'matricula' => 'required|string|max:20|unique:vehiculos,matricula,' . $id,
            'modelo' => 'required|string|max:60',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string|max:200',
            'disponibilidad' => 'required|in:disponible,no_disponible',
            'aire_acondicionado' => 'required|boolean',
            'conexion_bluetooth' => 'required|boolean',
            'marca.id' => 'required|exists:marcas,id',
            'imagenes' => 'array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $vehiculo = Vehiculo::findOrFail($id);

        // Actualizar los datos del vehículo
        $vehiculo->update($validatedData);

        // Verificar si la petición trae imágenes
        if ($request->hasFile('imagenes')) {
            // Eliminar imágenes antiguas
            foreach ($vehiculo->imagenes as $imagen) {
                $rutaImagen = public_path('images/vehiculos/') . $imagen->nombre;
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
                $imagen->delete();
            }

            // Guardar nuevas imágenes
            foreach ($request->file('imagenes') as $image) {
                $imageName = uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/vehiculos'), $imageName);

                Imagen::create([
                    'vehiculo_id' => $vehiculo->id,
                    'nombre' => $imageName
                ]);
            }
        }

        // Obtener el vehículo actualizado con más detalles si es necesario
        $vehiculoP = $this->show($vehiculo->id);

        return response()->json([
            "vehiculo" => $vehiculoP,
            "message" => "El vehículo se ha actualizado con éxito"
        ], 200);
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
            $vehiculo = Vehiculo::findOrFail($id);

            // Eliminar imágenes asociadas
            foreach ($vehiculo->imagenes as $imagen) {
                $rutaImagen = public_path('images/vehiculos/') . $imagen->nombre;
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
                $imagen->delete();
            }

            $vehiculo->delete();
            return response()->json(['message' => 'Vehículo eliminado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
