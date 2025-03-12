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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('matricula', 20)->unique();
            $table->string('modelo', 60);
            $table->decimal('precio', 8, 2);
            $table->string('descripcion', 200)->nullable();
            $table->enum('disponibilidad', ['disponible', 'no_disponible'])->default('disponible');
            $table->boolean('aire_acondicionado')->default(false);
            $table->boolean('conexion_bluetooth')->default(false);
            $table->unsignedBigInteger('marca_id');
            $table->timestamps();

            $table->foreign('marca_id')->references('id')->on('marcas');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
