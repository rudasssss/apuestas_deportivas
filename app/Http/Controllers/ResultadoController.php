<?php

namespace App\Http\Controllers;

use App\Models\Resultado;
use App\Models\Eventos;
use App\Models\Apuestas;
use Illuminate\Http\Request;
use App\Models\User;

class ResultadoController extends Controller
{

    public function generarResultado($evento_id)
    {

        $evento = Eventos::findOrFail($evento_id);

        $opciones = ['local','visitante','empate'];

        $resultadoFinal = $opciones[array_rand($opciones)];

        Resultado::create([
            'evento_id' => $evento->id,
            'resultado' => $resultadoFinal
        ]);

        $apuestas = Apuestas::where('evento_id',$evento->id)->get();
        $resultadoApuestas = [];

        foreach($apuestas as $apuesta){

            if($apuesta->tipo_apuesta == $resultadoFinal){

                $ganancia = $apuesta->monto * $apuesta->cuota;

                $user = User::find($apuesta->usuario_id);
                $user->saldo = $user->saldo + $ganancia;
                $user->save();

                $apuesta->update([
                    'estado' => 'ganada',
                    'ganancia' => $ganancia
                ]);

                $mensaje = "Ganaste";

            }else{

                $ganancia = 0;

                $apuesta->update([
                    'estado' => 'perdida',
                    'ganancia' => 0
                ]);

                $mensaje = "Perdiste";
            }

            $resultadoApuestas[] = [
                "usuario" => $apuesta->usuario_id,
                "mensaje" => $mensaje,
                "ganancia" => $ganancia
            ];

        }

        return response()->json([
            "evento" => $evento->equipo_local . " vs " . $evento->equipo_visitante,
            "resultado" => $resultadoFinal,
            "apuestas" => $resultadoApuestas
        ]);
    }

    public function verResultado()
    {
        $user = auth()->user();

        $resultados = Resultado::with('evento')->get();

        return response()->json($resultados->map(function($r) use ($user){

            $apuesta = Apuestas::where('evento_id',$r->evento_id)
                            ->where('usuario_id',$user->id)
                            ->first();

            if($apuesta){

                if($apuesta->tipo_apuesta == $r->resultado){
                    $mensaje = "Ganaste";
                }else{
                    $mensaje = "Perdiste";
                }

            }else{
                $mensaje = "No apostaste en este evento";
            }

            return [
                "evento" => $r->evento->equipo_local . " vs " . $r->evento->equipo_visitante,
                "resultado" => $r->resultado,
                "tu_apuesta" => $apuesta ? $apuesta->tipo_apuesta : null,
                "mensaje" => $mensaje
            ];
        }));
    }

}