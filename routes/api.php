<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ApuestasController;
use App\Http\Controllers\ResultadoController;

// Publico
Route::post('login', [AuthController::class, 'login']);
Route::post('verify', [AuthController::class, 'verify']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::get('me', [AuthController::class, 'me']);

//Autenticadas
Route::middleware('auth:api')->group(function () {

    //Control de las apuestas
    Route::get('me', [AuthController::class, 'me']);

    Route::get('miCuenta', [AuthController::class, 'miCuenta']);
    Route::post('cobrarSaldo', [AuthController::class, 'cobrarSaldo']);
    Route::get('eventos', [EventoController::class,'index'])->middleware('role:admin,usuario');
    Route::post('generar-eventos', [EventoController::class,'generarEventos'])->middleware('role:admin');
    Route::post('apostar', [ApuestasController::class,'apostar'])->middleware('role:admin,usuario');
    Route::get('mis-apuestas', [ApuestasController::class,'misApuestas'])->middleware('role:admin,usuario');
    Route::post('generar-resultado/{evento_id}', [ResultadoController::class,'generarResultado'])->middleware('role:admin');
    Route::get('ver-resultado', [ResultadoController::class,'verResultado'])->middleware('role:admin,usuario');

});

