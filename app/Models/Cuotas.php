<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuotas extends Model
{
    use HasFactory;

    protected $table = 'cuotas';

    protected $fillable = [
        'evento_id',
        'tipo_apuesta',
        'cuota'
    ];

    public function evento()
    {
        return $this->belongsTo(Eventos::class, 'evento_id');
    }
}
