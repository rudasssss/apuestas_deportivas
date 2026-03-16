<?php

namespace App\Http\Controllers;

use App\Models\Apuestas;
use Illuminate\Http\Request;
use App\Models\Eventos;

class ApuestasController extends Controller
{

    public function apostar(Request $request)
    {
        $evento = Eventos::findOrFail($request->evento_id);
        $apuesta = Apuestas::create([
            'usuario_id' => auth()->id(),
            'evento_id' => $request->evento_id,
            'tipo_apuesta' => $request->tipo_apuesta,
            'monto' => $request->monto,
            'cuota' => $request->cuota,
            'estado' => 'pendiente'
        ]);

        return response()->json([
            "mensaje" => "Apuesta creada",
            "evento" => $evento->equipo_local . " vs " . $evento->equipo_visitante,
            "monto" => $request->monto
        ]);
    }

    public function misApuestas()
    {
        $apuestas = Apuestas::with('evento')
            ->where('usuario_id', auth()->id())
            ->get();

        return response()->json($apuestas->map(function($apuesta){
            return [
                "evento" => $apuesta->evento->equipo_local . " vs " . $apuesta->evento->equipo_visitante,
                "tipo_apuesta" => $apuesta->tipo_apuesta,
                "cuota" => $apuesta->cuota,
                "monto" => $apuesta->monto
            ];
        }));
    }

}