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
        Schema::create('laboratorio', function(Blueprint $table){
            $table->id('idLaboratorio');
            $table->unsignedBigInteger('fkTipo');
            $table->integer('capacidad');
            $table->foreign('fkTipo')->references('idReferencia')->on('referencia')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio');
    }
};
