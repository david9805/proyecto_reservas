<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipoReferenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //seeder para agregar tipo de referencias
        DB::table('tipoReferencia')->insert(
            [
                [
                    'tipoReferencia' => 'tipoLaboratorio'
                ],
                [
                    'tipoReferencia' => 'tipoUsuario'
                ],
                [
                    'tipoReferencia' => 'dependencia'
                ]
            ]
            
        );
    }
}
