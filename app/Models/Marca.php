<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relación con la tabla `vehiculos`
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}
