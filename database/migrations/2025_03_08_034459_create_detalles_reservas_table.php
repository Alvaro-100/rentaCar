<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('detalles_reservas', function (Blueprint $table) {
        $table->id();
        $table->date('fecha_entrega');
        $table->date('fecha_retiro');
        $table->decimal('monto_total', 6, 2);
        $table->unsignedBigInteger('kilometraje_inicial');
        $table->unsignedBigInteger('kilometraje_final')->nullable();
        $table->unsignedBigInteger('reserva_id');
        $table->unsignedBigInteger('vehiculo_id');
        $table->timestamps();

        $table->foreign('reserva_id')->references('id')->on('reservas');
        $table->foreign('vehiculo_id')->references('id')->on('vehiculos');

        $table->unique(['reserva_id', 'vehiculo_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_reservas');
    }
};
