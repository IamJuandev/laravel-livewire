<?php

namespace App\Livewire\Invitaciones;

use App\Models\Estudiante;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    #[Url]
    public $perPage = 10;
    #[Url]
    public $sortField = 'id';
    #[Url]
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $estudiantes = Estudiante::with('carrera', 'qr')
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.invitaciones.index', [
            'estudiantes' => $estudiantes,
        ]);
    }
}
