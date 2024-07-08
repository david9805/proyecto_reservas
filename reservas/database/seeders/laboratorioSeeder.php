<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class laboratorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     // creacion seeder para el ejemplo oportuno
    public function run(): void
    {        
        //consulta para traer la fk de la tabla referencias
        $fotografia = DB::table('referencia')->where('nombreReferencia','Fotografia')->value('idReferencia');
        $sonido = DB::table('referencia')->where('nombreReferencia','Sonido')->value('idReferencia');
        $video = DB::table('referencia')->where('nombreReferencia','Video')->value('idReferencia');
        
        // seeder para agregar laboratorios
        DB::table('laboratorio')->insert(
            [
                [
                    'capacidad'=>20,
                    'fkTipo'=>$fotografia
                ],
                [
                    'capacidad'=>30,
                    'fkTipo'=>$video
                ],
                [
                    'capacidad'=>25,
                    'fkTipo'=>$sonido
                ]
            ]
        );
    }
}
