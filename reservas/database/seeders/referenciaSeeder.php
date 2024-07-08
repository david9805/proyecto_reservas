<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class referenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //consulta para traer la fk de la tabla tipoReferencia
        $fkTipoUsuario = DB::table('tipoReferencia')->where('tipoReferencia','tipoUsuario')->value('idTipoReferencia');
        $fkTipoLaboratorio = DB::table('tipoReferencia')->where('tipoReferencia','tipoLaboratorio')->value('idTipoReferencia');
        $fkDependencia = DB::table('tipoReferencia')->where('tipoReferencia','dependencia')->value('idTipoReferencia');
        // seeder para agregar referencias
        DB::table('referencia')->insert(
            [
                [
                    'nombreReferencia'=>'Estudiante',
                    'fkTipoReferencia'=>$fkTipoUsuario
                ],
                [
                    'nombreReferencia'=>'Docente',
                    'fkTipoReferencia'=>$fkTipoUsuario
                ],
                [
                    'nombreReferencia'=>'Administrativo',
                    'fkTipoReferencia'=>$fkTipoUsuario
                ],
                [
                    'nombreReferencia'=>'Fotografia',
                    'fkTipoReferencia'=>$fkTipoLaboratorio
                ],
                [
                    'nombreReferencia'=>'Video',
                    'fkTipoReferencia'=>$fkTipoLaboratorio
                ],
                [
                    'nombreReferencia'=>'Sonido',
                    'fkTipoReferencia'=>$fkTipoLaboratorio
                ],
                [
                    'nombreReferencia'=>'Decanatura sistemas',
                    'fkTipoReferencia'=>$fkDependencia
                ],
                [
                    'nombreReferencia'=>'Decanatura administracion',
                    'fkTipoReferencia'=>$fkDependencia
                ],
                [
                    'nombreReferencia'=>'Ingeniero de sistemas',
                    'fkTipoReferencia'=>$fkDependencia
                ],
                [
                    'nombreReferencia'=>'Administrador de empresas',
                    'fkTipoReferencia'=>$fkDependencia
                ]
            ]
        );
    }
}
