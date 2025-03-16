<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'matricula', 'modelo', 'precio', 'descripcion', 'disponibilidad', 
        'aire_acondicionado', 'conexion_bluetooth', 'marca_id'
    ];

    // Relación con la tabla `imagenes`
    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }

    // Relación con la tabla `marca`
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
