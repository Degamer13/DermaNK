<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HistoriaMedica extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'historia_medica';

    /**
     * IMPORTANTE:
     * Aquí definimos qué campos permite Laravel guardar.
     * Si falta alguno aquí, Livewire no podrá guardarlo.
     */
    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'direccion',
        'telefono',
        'telefono_casa',
        'email',
        'profesion',
        'ocupacion',
        'referido',
        'estado_civil',
        'genero',
        'seguro',
        'medico',
    ];

    /**
     * Casts: Convertimos tipos de datos automáticamente.
     * 'date' nos permite usar funciones como ->age (edad) o ->format().
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Accessor: Código de Historia (HM-00001)
     * Se usa en la vista como: $historia->codigo_historia
     */
    protected function codigoHistoria(): Attribute
    {
        return Attribute::make(
            get: fn () => 'HM-' . str_pad($this->id, 6, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Accessor: Nombre Completo
     * Se usa en la vista como: $historia->nombre_completo
     */
    protected function nombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->nombres} {$this->apellidos}",
        );
    }
}
