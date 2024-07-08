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
        Schema::create('usuario',function (Blueprint $table){
            $table->id('idUsuario');
            $table->unsignedBigInteger('fkTipoUsuario');
            $table->string('nombre');
            $table->string('identificacion');
            $table->unsignedBigInteger('fkDependencia');
            $table->foreign('fkTipoUsuario')->references('idReferencia')->on('referencia')->onDelete('cascade');
            $table->foreign('fkDependencia')->references('idReferencia')->on('referencia')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
