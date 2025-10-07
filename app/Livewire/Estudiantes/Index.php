<?php

namespace App\Livewire\Estudiantes;

use App\Models\Estudiante;
use App\Models\Carrera; // Added
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Exports\EstudiantesExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads; // Added
use App\Imports\EstudiantesImport; // Added

class Index extends Component
{
    use WithPagination, WithFileUploads; // Added

    // Changed 'carrera' to 'carrera_id' and added 'all_carreras'
    public $nombres, $apellidos, $identificacion, $carrera_id, $student_id;
    public $all_carreras = [];
    public $archivo_importacion; // Added

    public function render()
    {
        // Eager-load the carrera relationship
        $estudiantes = Estudiante::with('carrera')->paginate(10);

        return view('livewire.estudiantes.index', [
            'estudiantes' => $estudiantes,
        ]);
    }

    public function export()
    {
        return Excel::download(new EstudiantesExport, 'estudiantes.xlsx');
    }

    public function importar()
    {
        $this->validate([
            'archivo_importacion' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new EstudiantesImport, $this->archivo_importacion);

        session()->flash('message', 'Estudiantes importados con éxito.');

        $this->archivo_importacion = null;
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->all_carreras = Carrera::all(); // Load carreras for dropdown
    }

    private function resetCreateForm()
    {
        $this->nombres = '';
        $this->apellidos = '';
        $this->identificacion = '';
        $this->carrera_id = null; // Changed
        $this->student_id = null;
        $this->all_carreras = []; // Reset
    }

    public function edit($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $this->student_id = $id;
        $this->nombres = $estudiante->nombres;
        $this->apellidos = $estudiante->apellidos;
        $this->identificacion = $estudiante->identificacion;
        $this->carrera_id = $estudiante->carrera_id; // Changed
        $this->all_carreras = Carrera::all(); // Load carreras for dropdown
    }

    public function save()
    {
        $this->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'identificacion' => ['required', 'string', 'max:20', Rule::unique('estudiantes')->ignore($this->student_id)],
            'carrera_id' => 'required|exists:carreras,id', // Changed
        ]);

        Estudiante::updateOrCreate(['id' => $this->student_id], [
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'identificacion' => $this->identificacion,
            'carrera_id' => $this->carrera_id, // Changed
        ]);

        session()->flash('message', 
            $this->student_id ? 'Estudiante actualizado con éxito.' : 'Estudiante creado con éxito.');

        $this->dispatch('close-modal');
        $this->resetCreateForm();
    }

    public function delete($id)
    {
        Estudiante::find($id)->delete();
        session()->flash('message', 'Estudiante eliminado con éxito.');
    }
}