<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- 1. IMPORTAR ESTO
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patologia extends Model
{
    use HasFactory; // <--- 2. USARLO DENTRO DE LA CLASE

    protected $fillable = [
        'historia_medica_id',
        'nombre',
        'observaciones'
    ];

    public function historiaMedica(): BelongsTo
    {
        return $this->belongsTo(HistoriaMedica::class, 'historia_medica_id');
    }
}
