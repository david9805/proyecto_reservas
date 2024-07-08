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
        Schema::create('reservas', function (Blueprint $table){
            $table->id('idReservas');
            $table->unsignedBigInteger('fkLaboratorio');
            $table->unsignedBigInteger('fkUsuario');
            $table->date('fechaSolicitud')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->time('horaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->time('horaFin')->nullable();
            $table->text('descripcion');
            $table->boolean('estado')->default(true);
            $table->foreign('fkLaboratorio')->references('idLaboratorio')->on('laboratorio')->onDelete('cascade');
            $table->foreign('fkUsuario')->references('idUsuario')->on('usuario')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
