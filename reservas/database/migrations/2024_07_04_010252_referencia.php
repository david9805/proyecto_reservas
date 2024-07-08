<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     // al tener el tipo referencia puedo agregar la informacion que corresponde
    public function up(): void
    {
        Schema::create('referencia', function(Blueprint $table){
            $table->id('idReferencia');
            $table->string('nombreReferencia');
            $table->unsignedBigInteger('fkTipoReferencia');

            $table->foreign('fkTipoReferencia')->references('idTipoReferencia')->on('tipoReferencia')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencia');
    }
};
