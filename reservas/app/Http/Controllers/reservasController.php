<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class reservasController extends Controller
{
    public function getAllReservas(Request $request){

        // parametros para la paginacion
        $page = $request->query('page') ? (int)$request->query('page') : 0;
        $pageElements = $request->query('pageElements') ? (int)$request->query('pageElements') : 8;

        // consulta apuntando a las tablas hijas
        $reservas = DB::table('reservas')
        ->join('laboratorio','laboratorio.idLaboratorio','=','reservas.fkLaboratorio')
        ->join('usuario','usuario.idUsuario','=','reservas.fkUsuario')
        ->join('referencia','referencia.idReferencia','=','laboratorio.fkTipo')
        ->where('estado',true)
        ->distinct() ;

        // punto en el que se tomara la informacion
        $offset = $page * $pageElements;

        // cuenta el total de columnas
        $total = $reservas->count();
        //trae la informacion
        $data = $reservas->skip($offset)->take($pageElements)->get();

        //envio la informacion
        return response()->json([
            'error' => 0,
            'data' => $data,
            'total'=>$total
        ]);
    }

    public function getByIdReservas($id, Request $request){

        //traer informacion por id
        $data = DB::table('reservas')->where('idReservas',$id)->first();

        //envio la informacion
        return response()->json([
            'error' => 0,
            'data' => $data
        ]);
    }

    public function getAllLaboratorios(){

        // informacion de los laboratorios con su tabla hija
        $data = DB::table('laboratorio')
        ->join('referencia','referencia.idReferencia','=','laboratorio.fkTipo')
        ->get();

        // envio de informacion de laboratorios
        return response()->json([
            'error' => 0,
            'data' => $data
        ]);

    }

    public function getAllUsuario(){

        // informacion de usuarios
        $data = DB::table('usuario')->get();

        //envio de informacion de usuarios
        return response()->json([
            'error' => 0,
            'data' => $data
        ]);
    }

    public function postReservas(Request $request){

        // tomo la informacion enviada por el body del api y la convierto en un array
        $body = (array)json_decode($request->getContent());

        // selecciono cada item del json convertido en array
        $fkLaboratorio = $body["fkLaboratorio"];
        $fkUsuario = $body["fkUsuario"];
        $fechaSolicitud = Carbon::now()->toDateString();
        $fechaInicio = $body["fechaInicio"];
        $horaInicio = $body["horaInicio"];
        $fechaFin = $body["fechaFin"];
        $horaFin = $body["horaFin"];
        $descripcion = $body["descripcion"];
        $estado = true;

        // condicionales para validar que no este vacia la informacion
        if (!isset($fkLaboratorio)){
            return response()->json(
            [
                'error'=>1,
                'message'=>'Debe seleccionar un laboratorio'
            ]
            ,400);
        }

        if (!isset($fkUsuario)){
            return response()->json(
            [
                'error'=>1,
                'message'=>'Debe seleccionar un usuario'
            ]
            ,400);
        }

        if (!isset($fechaInicio)){
            return response()->json(
            [
                'error'=>1,
                'message'=>'Debe digitar una fecha de inicio'
            ]
            ,400);
        }

        if (!isset($horaInicio)){
            return response()->json(
            [
                'error'=>1,
                'message'=>'Debe digitar una hora de inicio'
            ]
            ,400);
        }

        if (!isset($fechaFin)){
            return response()->json(
            [
                'error'=>1,
                'message'=>'Debe digitar una fecha de finalizacion'
            ]
            ,400);
        }

        if (!isset($horaFin)){
            return response()->json(
            [
                'error'=>1,
                'message'=>'Debe digitar una hora de finalizacion'
            ]
            ,400);
        }

        // se revisa que la hora de inicio no sea mayor que la hora fin
        if($horaInicio > $horaFin){
            return response()->json(
                [
                    'error'=>1,
                    'message'=>'La hora de inicio no puede ser mayor a la hora de finalizacion'
                ]
                ,400);
        }

        // se revisa que la hora de fin no sea menor que la hora inicio
        if($horaFin < $horaInicio){
            return response()->json(
                [
                    'error'=>1,
                    'message'=>'La hora de finalizacion no puede ser menor a la hora de inicio'
                ]
                ,400);
        }

        // Convertir la hora de inicio a un objeto Carbon (suponiendo que está en formato 'H:i')
        $horaInicioCarbon = Carbon::parse($horaInicio);

        // Convertir fechaInicio a objeto Carbon
        $fechaInicioCarbon = Carbon::parse($fechaInicio);

        // Sumar la hora de inicio a la fecha de inicio
        $fechaInicioCarbon->setTime($horaInicioCarbon->hour+5, $horaInicioCarbon->minute, 0);

        // Obtener el timestamp Unix
        $fechaInicioUnix = $fechaInicioCarbon->timestamp;

        // Convertir la hora de finalizacion a un objeto Carbon (suponiendo que está en formato 'H:i')
        $horaFinCarbon = Carbon::parse($horaFin);

        // Convertir fechaFin a objeto Carbon
        $fechaFinCarbon = Carbon::parse($fechaFin);

        // Sumar la hora de finalizacion a la fecha de finalizacion
        $fechaFinCarbon->setTime($horaFinCarbon->hour+5, $horaFinCarbon->minute, 0);

        // Obtener el timestamp Unix
        $fechaFinUnix = $fechaFinCarbon->timestamp;

        
        // consulta con las respectivas condicones para validar que no se reserve un mismo espacio en el mismo horario
        $reservas = DB::table('reservas')
        ->where('fkLaboratorio', $fkLaboratorio)
        ->where(function ($query) use ($fechaInicioUnix, $fechaFinUnix) {
            $query->whereRaw('? BETWEEN CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaInicio, " ", horaInicio), INTERVAL 0 SECOND)) AS UNSIGNED) AND CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaFin, " ", horaFin), INTERVAL 0 SECOND)) AS UNSIGNED)', [$fechaInicioUnix])
                ->orWhereRaw('? BETWEEN CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaInicio, " ", horaInicio), INTERVAL 0 SECOND)) AS UNSIGNED) AND CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaFin, " ", horaFin), INTERVAL 0 SECOND)) AS UNSIGNED)', [$fechaFinUnix]);
        })
        ->exists();

        // si existe una reserva no deja insertar
        if($reservas){
            return response()->json(
                [
                    'error'=>1,
                    'message'=>'La fecha de la reserva no esta disponible'
                ]
                ,400);
        }

        // en caso de que no exista inserta los datos correcatamente
        $data = DB::table('reservas')->insert(
            [
                'fkLaboratorio' => $fkLaboratorio,
                'fkUsuario' => $fkUsuario,
                'fechaSolicitud' => $fechaSolicitud,
                'fechaInicio'  => $fechaInicio,
                'horaInicio' => $horaInicio,
                'fechaFin' => $fechaFin,
                'horaFin' => $horaFin,
                'descripcion' => $descripcion,
                'estado' => $estado,
                'created_at'=>Carbon::now()->toDateString()
            ]
        );

        return response()->json(
            [
                'error'=>0,
                'data'=>$data
            ]
        );
    }

    public function putReservas($id,Request $request){
        try{

            // tomo la informacion enviada por el body del api y la convierto en un array
            $body = (array)json_decode($request->getContent());

            // tomo cada item del json convertido en array
            $fkLaboratorio = $body["fkLaboratorio"];
            $fkUsuario = $body["fkUsuario"];
            $fechaInicio = $body["fechaInicio"];
            $horaInicio = $body["horaInicio"];
            $fechaFin = $body["fechaFin"];
            $horaFin = $body["horaFin"];
            $descripcion = $body["descripcion"];

            // serie de condicionales para validar que ningun campo pase vacio
            if (!isset($fkLaboratorio)){
                return response()->json(
                [
                    'error'=>1,
                    'message'=>'Debe seleccionar un laboratorio'
                ]
                ,400);
            }

            if (!isset($fkUsuario)){
                return response()->json(
                [
                    'error'=>1,
                    'message'=>'Debe seleccionar un usuario'
                ]
                ,400);
            }

            if (!isset($fechaInicio)){
                return response()->json(
                [
                    'error'=>1,
                    'message'=>'Debe digitar una fecha de inicio'
                ]
                ,400);
            }

            if (!isset($horaInicio)){
                return response()->json(
                [
                    'error'=>1,
                    'message'=>'Debe digitar una hora de inicio'
                ]
                ,400);
            }

            if (!isset($fechaFin)){
                return response()->json(
                [
                    'error'=>1,
                    'message'=>'Debe digitar una fecha de finalizacion'
                ]
                ,400);
            }

            if (!isset($horaFin)){
                return response()->json(
                [
                    'error'=>1,
                    'message'=>'Debe digitar una hora de finalizacion'
                ]
                ,400);
            }

            if($horaInicio > $horaFin){
                return response()->json(
                    [
                        'error'=>1,
                        'message'=>'La hora de inicio no puede ser mayor a la hora de finalizacion'
                    ]
                    ,400);
            }

            if($horaFin < $horaInicio){
                return response()->json(
                    [
                        'error'=>1,
                        'message'=>'La hora de finalizacion no puede ser menor a la hora de inicio'
                    ]
                    ,400);
            }

            // Convertir la hora de inicio a un objeto Carbon (suponiendo que está en formato 'H:i')
            $horaInicioCarbon = Carbon::parse($horaInicio);

            // Convertir fechaInicio a objeto Carbon
            $fechaInicioCarbon = Carbon::parse($fechaInicio);

            // Sumar la hora de inicio a la fecha de inicio
            $fechaInicioCarbon->setTime($horaInicioCarbon->hour+5, $horaInicioCarbon->minute, 0);

            // Obtener el timestamp Unix
            $fechaInicioUnix = $fechaInicioCarbon->timestamp;

            // Convertir la hora de finalizacion a un objeto Carbon (suponiendo que está en formato 'H:i')
            $horaFinCarbon = Carbon::parse($horaFin);

            // Convertir fechaFin a objeto Carbon
            $fechaFinCarbon = Carbon::parse($fechaFin);

            // Sumar la hora de finalizacion a la fecha de finalizacion
            $fechaFinCarbon->setTime($horaFinCarbon->hour+5, $horaFinCarbon->minute, 0);

            // Obtener el timestamp Unix
            $fechaFinUnix = $fechaFinCarbon->timestamp;

            // Ahora $timestamp contiene el timestamp Unix con la hora sumada correctamente

            // esta condicion es la misma del post pero se adiciona que sea diferente el idReserva que se esta actualizando
            $reservasExists = DB::table('reservas')
            ->where('fkLaboratorio', $fkLaboratorio)
            ->whereNot('idReservas',$id)
            ->where(function ($query) use ($fechaInicioUnix, $fechaFinUnix) {
                $query->whereRaw('? BETWEEN CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaInicio, " ", horaInicio), INTERVAL 0 SECOND)) AS UNSIGNED) AND CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaFin, " ", horaFin), INTERVAL 0 SECOND)) AS UNSIGNED)', [$fechaInicioUnix])
                    ->orWhereRaw('? BETWEEN CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaInicio, " ", horaInicio), INTERVAL 0 SECOND)) AS UNSIGNED) AND CAST(UNIX_TIMESTAMP(DATE_ADD(CONCAT(fechaFin, " ", horaFin), INTERVAL 0 SECOND)) AS UNSIGNED)', [$fechaFinUnix]);
            })
            ->exists();

            //se valida si existe reserva no se deja actualizar
            if($reservasExists){
                return response()->json(
                    [
                        'error'=>1,
                        'message'=>'La fecha de la reserva no esta disponible'
                    ]
                    ,400);
            }
            

            // si no existe actualiza
            $data = DB::table('reservas')->where('idReservas', $id)->update([
                'fkLaboratorio' => $fkLaboratorio,
                'fkUsuario' => $fkUsuario,
                'fechaInicio' => $fechaInicio,
                'horaInicio' => $horaInicio,
                'fechaFin' => $fechaFin,
                'horaFin' => $horaFin,
                'descripcion' => $descripcion,
                'updated_at'=> Carbon::now()->toDateString()
            ]);        

            return response()->json(
                [
                    'error'=>0,
                    'data'=>$data
                ]
            );
        }catch(Exception $e){

            return response()->json(
                [
                    'error'=>1,
                    'message'=>$e->getMessage()
                ]
                ,400);
        }
    }

    public function deleteReservas($id){
        try{
            // en este caso me gusta crear un campo estado y jugar con estados, no eliminar la informacion
            $data = DB::table('reservas')->where('idReservas', $id)->update([
                'estado' => false,
            ]);

            return response()->json(
                [
                    'error'=>0,
                    'data'=>$data
                ]
            );
        }
        catch(Exception $e){
            return response()->json(
                [
                    'error'=>1,
                    'message'=>$e->getMessage()
                ]
                ,400);
        }
    }
}
