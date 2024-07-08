<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class usuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrativo = DB::table('referencia')->where('nombreReferencia','Administrativo')->value('idReferencia');
        $docente = DB::table('referencia')->where('nombreReferencia','Docente')->value('idReferencia');
        $estudiante = DB::table('referencia')->where('nombreReferencia','Estudiante')->value('idReferencia');
        $decanaturaSistemas = DB::table('referencia')->where('nombreReferencia','Decanatura sistemas')->value('idReferencia');
        $decanaturaAdministrador = DB::table('referencia')->where('nombreReferencia','Decanatura administracion')->value('idReferencia');
        $ingenieroSistemas = DB::table('referencia')->where('nombreReferencia','Ingeniero de sistemas')->value('idReferencia');


        DB::table('usuario')->insert(
            [
                [
                'nombre'=>'Elkin Soto',
                'identificacion'=> '884433234',
                'fkTipoUsuario'=>$administrativo,
                'fkDependencia'=>$decanaturaSistemas
                ],
                [
                    'nombre'=>'Marina Granciera',
                    'identificacion'=> '43512362312',
                    'fkTipoUsuario'=>$docente,
                    'fkDependencia'=>$decanaturaAdministrador
                ],
                [
                    'nombre'=>'Ricardo Jorge',
                    'identificacion'=> '6456453',
                    'fkTipoUsuario'=>$estudiante,
                    'fkDependencia'=>$ingenieroSistemas
                ]
            ]
        );
    }
}
