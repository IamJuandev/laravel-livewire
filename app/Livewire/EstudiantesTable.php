<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Estudiante;

class EstudiantesTable extends DataTableComponent
{
    protected $model = Estudiante::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // Removed: $this->setExporting();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombres", "nombres")
                ->sortable()
                ->searchable(),
            Column::make("Apellidos", "apellidos")
                ->sortable()
                ->searchable(),
            Column::make("Identificacion", "identificacion")
                ->sortable()
                ->searchable(),
            Column::make("Carrera", "carrera.nombre")
                ->sortable()
                ->searchable(),
        ];
    }

    // Removed: public function setToolbar()
    // Removed: public function importar()
}