<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\reservasController;

use App\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});
// rutas a utilizar por el front o una app externa
Route::get('/reservas',[reservasController::class,'getAllReservas']);
Route::get('/reservas/{id}',[reservasController::class,'getByIdReservas']);
Route::get('/laboratorios',[reservasController::class,'getAllLaboratorios']);
Route::get('/usuarios',[reservasController::class,'getAllUsuario']);
Route::post('/reservas',[reservasController::class,'postReservas']);
Route::put('/reservas/{id}',[reservasController::class,'putReservas']);
Route::delete('/reservas/{id}',[reservasController::class,'deleteReservas']);
