<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallereserva extends Model
{
    use HasFactory;

    protected $table = 'detalles_reservas';

    protected $fillable = ['fecha_entrega', 'fecha_retiro', 'monto_total', 'kilometraje_inicial', 'kilometraje_final', 'reserva_id', 'vehiculo_id'];

    // Relación: Un detalle pertenece a una reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    // Relación: Un detalle pertenece a un vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
