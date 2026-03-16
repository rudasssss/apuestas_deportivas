<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\Cuotas;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function generarEventos()
    {
        Cuotas::query()->delete();
        Eventos::query()->delete();

        $equipos = [
            "Real Madrid",
            "Barcelona",
            "Manchester City",
            "Liverpool",
            "PSG",
            "Bayern Munich",
            "Juventus",
            "Arsenal"
        ];

        $eventos = [];

        for ($i = 0; $i < 5; $i++) {

            $local = $equipos[array_rand($equipos)];
            $visitante = $equipos[array_rand($equipos)];

            while ($local == $visitante) {
                $visitante = $equipos[array_rand($equipos)];
            }

            $evento = Eventos::create([
                'deporte' => 'Futbol',
                'equipo_local' => $local,
                'equipo_visitante' => $visitante,
                'fecha' => now()->addDays(rand(1,5)),
                'estado' => 'pendiente'
            ]);

            Cuotas::create([
                'evento_id' => $evento->id,
                'tipo_apuesta' => 'local',
                'cuota' => rand(18,25)/10
            ]);

            Cuotas::create([
                'evento_id' => $evento->id,
                'tipo_apuesta' => 'empate',
                'cuota' => rand(18,25)/10
            ]);

            Cuotas::create([
                'evento_id' => $evento->id,
                'tipo_apuesta' => 'visitante',
                'cuota' => rand(18,25)/10
            ]);

            $eventos[] = $evento;
        }

        return response()->json($eventos);
    }


    public function index()
    {
        return response()->json(Eventos::with('cuotas')->get());
    }
}