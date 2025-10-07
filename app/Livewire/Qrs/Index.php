<?php

namespace App\Livewire\Qrs;

use App\Models\Estudiante;
use App\Models\Carrera;
use App\Models\Qr;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $numeroDeQrs;
    public $numeroDeEntradas;
    public $fechaDelEvento;

    // Properties for Mass Assignment
    public $carrera_seleccionada = '';
    public $carreras_disponibles = [];
    public $student_count_for_selected_carrera = 0;


    // Properties for Edit Modal
    public $qr_id, $code, $edit_numero_de_entradas, $edit_estudiante_id;
    public $all_students;

    public function mount()
    {
        $this->carreras_disponibles = Carrera::pluck('nombre');
    }

    public function render()
    {
        // Eager load the student relationship to prevent N+1 issues in the view
        $qrs = Qr::with('estudiante.carrera')->latest()->paginate(10);
        return view('livewire.qrs.index', [
            'qrs' => $qrs
        ]);
    }

    public function generarQrs()
    {
        $this->validate([
            'numeroDeQrs' => 'required|integer|min:1|max:500', // Reduced max for performance
            'numeroDeEntradas' => 'required|integer|min:1|max:100',
            'fechaDelEvento' => 'required|date',
        ]);

        $path = 'public/qrs';
        Storage::makeDirectory($path);

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        for ($i = 0; $i < $this->numeroDeQrs; $i++) {
            $uuid = (string) Str::uuid();
            $filename = 'qr-' . $this->fechaDelEvento . '-' . $uuid . '.svg';
            $fullPath = "{$path}/{$filename}";
            $publicPath = 'storage/qrs/' . $filename;

            $writer->writeFile($uuid, storage_path('app/' . $fullPath));

            Qr::create([
                'code' => $uuid,
                'path' => $publicPath,
                'numero_de_entradas' => $this->numeroDeEntradas,
                'fecha_del_evento' => $this->fechaDelEvento,
            ]);
        }
        
        session()->flash('message', $this->numeroDeQrs . ' códigos QR generados y guardados.');
    }

    public function asignarPorCarrera()
    {
        $this->validate([
            'carrera_seleccionada' => 'required|string|exists:carreras,nombre'
        ]);

        $carreraModel = Carrera::where('nombre', $this->carrera_seleccionada)->first();

        // Find students from the selected career who don't have a QR code yet.
        $estudiantesSinQr = Estudiante::where('carrera_id', $carreraModel->id)
            ->whereNotIn('id', function($query) {
                $query->select('estudiante_id')->from('qrs')->whereNotNull('estudiante_id');
            })
            ->get();

        // Find QRs that are not assigned to any student.
        $qrsSinAsignar = Qr::whereNull('estudiante_id')->get();

        $asignacionesRealizadas = 0;
        $limite = min(count($estudiantesSinQr), count($qrsSinAsignar));

        for ($i = 0; $i < $limite; $i++) {
            $qr = $qrsSinAsignar[$i];
            $estudiante = $estudiantesSinQr[$i];
            
            $qr->estudiante_id = $estudiante->id;
            $qr->save();
            $asignacionesRealizadas++;
        }

        if ($asignacionesRealizadas > 0) {
            session()->flash('message', $asignacionesRealizadas . ' QRs han sido asignados a estudiantes de ' . $this->carrera_seleccionada . '.');
        } else {
            session()->flash('message', 'No se realizaron nuevas asignaciones. No hay suficientes QRs disponibles o todos los estudiantes de la carrera ya tienen uno.');
        }

        // Reset the dropdown
        $this->carrera_seleccionada = '';
    }

    public function updatedCarreraSeleccionada($carreraNombre)
    {
        if (!empty($carreraNombre)) {
            $carreraModel = Carrera::where('nombre', $carreraNombre)->first();
            if ($carreraModel) {
                $this->student_count_for_selected_carrera = Estudiante::where('carrera_id', $carreraModel->id)
                    ->whereNotIn('id', function($query) {
                        $query->select('estudiante_id')->from('qrs')->whereNotNull('estudiante_id');
                    })
                    ->count();
            } else {
                $this->student_count_for_selected_carrera = 0;
            }
        } else {
            $this->student_count_for_selected_carrera = 0;
        }
    }
    
    private function resetEditForm()
    {
        $this->qr_id = null;
        $this->code = '';
        $this->edit_numero_de_entradas = '';
        $this->edit_estudiante_id = null;
        $this->all_students = [];
    }

    public function edit($id)
    {
        $qr = Qr::findOrFail($id);
        $this->qr_id = $id;
        $this->code = $qr->code;
        $this->edit_numero_de_entradas = $qr->numero_de_entradas;
        $this->edit_estudiante_id = $qr->estudiante_id;
        $this->all_students = Estudiante::all(); // Load all students for the dropdown
    }

    public function save()
    {
        $this->validate([
            'edit_numero_de_entradas' => 'required|integer|min:0',
            'edit_estudiante_id' => 'nullable|exists:estudiantes,id'
        ]);

        if ($this->qr_id) {
            $qr = Qr::find($this->qr_id);
            $qr->update([
                'numero_de_entradas' => $this->edit_numero_de_entradas,
                'estudiante_id' => $this->edit_estudiante_id ?: null,
            ]);
        }

        session()->flash('message', 'QR actualizado con éxito.');
        $this->dispatch('close-modal');
        $this->resetEditForm();
    }

    public function delete($id)
    {
        $qr = Qr::find($id);
        if ($qr) {
            // The path is stored as 'storage/qrs/filename.svg'. We need to convert to 'public/qrs/filename.svg' for the Storage facade.
            $storagePath = str_replace('storage/', 'public/', $qr->path);
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
            $qr->delete();
            session()->flash('message', 'QR eliminado con éxito.');
        }
    }
}