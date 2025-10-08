<?php

namespace App\Livewire\Invitacion;

use App\Models\Estudiante;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.guest')] // Usar un layout de invitado sin navegación
class Show extends Component
{
    public Estudiante $estudiante;

    public function mount(Estudiante $estudiante)
    {
        $this->estudiante = $estudiante->load('carrera', 'qr');
    }

    public function render()
    {
        return view('livewire.invitacion.show');
    }
}
