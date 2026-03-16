<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apuestas extends Model
{
    use HasFactory;

    protected $table = 'apuestas';

    protected $fillable = [
        'usuario_id',
        'evento_id',
        'tipo_apuesta',
        'monto',
        'cuota',
        'estado',
        'ganancia'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function evento()
    {
        return $this->belongsTo(Eventos::class);
    }

}
