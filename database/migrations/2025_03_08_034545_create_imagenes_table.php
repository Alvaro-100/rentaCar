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
    {Schema::create('imagenes', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 150);
        $table->unsignedBigInteger('vehiculo_id');
        $table->timestamps();

        $table->foreign('vehiculo_id')->references('id')->on('vehiculos');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
