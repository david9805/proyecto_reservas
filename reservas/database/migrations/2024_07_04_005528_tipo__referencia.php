<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     // creo una tabla tipo referencia con el fin de insertar multiples tablas que cumplen objetivo de llenar combobox, asi me ahorro la creacion de multiples tablas que tienen este fin
    public function up(): void
    {
        Schema::create('tipoReferencia', function (Blueprint $table) {
            $table->id('idTipoReferencia');
            $table->string('tipoReferencia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExits('tipoReferencia');
    }
};
