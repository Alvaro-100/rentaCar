<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagenes';

    protected $fillable = ['nombre', 'vehiculo_id'];

    public $timestamps = false;

    // Relación: Una imagen pertenece a un vehículo
    public function vehiculo()
{
    return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
}

}
