<?php

namespace App\Livewire;

use Livewire\Component;

class BuscadorMedico extends Component
{
    public $busqueda = '';
    public $motor = 'google'; // google, vademecum, medscape

    public function render()
    {
        return view('livewire.buscador-medico.buscador-medico');
    }
}
