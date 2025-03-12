<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'matricula', 'modelo', 'precio', 'descripcion', 
        'aire_acondicionado', 'conexion_bluetooth', 'marca_id'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function imagenes()
{
    return $this->hasMany(Imagen::class, 'vehiculo_id');
}

    public function detallesReservas()
    {
        return $this->hasMany(DetalleReserva::class);
    }
}
