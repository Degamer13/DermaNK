<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $guarded = [];

    // Importante para que Livewire maneje la fecha correctamente
    protected $casts = [
        'fecha' => 'date',
    ];

    public function paciente()
    {
        return $this->belongsTo(HistoriaMedica::class, 'historia_medica_id');
    }

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }
}
