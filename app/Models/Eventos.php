<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'deporte',
        'equipo_local',
        'equipo_visitante',
        'fecha',
        'estado'
    ];

    public function cuotas()
    {
        return $this->hasMany(Cuotas::class, 'evento_id');
    }

    public function apuestas()
    {
        return $this->hasMany(Apuestas::class);
    }

    public function resultado()
    {
        return $this->hasOne(Resultados::class);
    }
}
