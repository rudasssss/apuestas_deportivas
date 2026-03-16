<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'resultados';

    protected $fillable = [
        'evento_id',
        'resultado'
    ];

    public function evento()
    {
        return $this->belongsTo(Eventos::class, 'evento_id');
    }
}
