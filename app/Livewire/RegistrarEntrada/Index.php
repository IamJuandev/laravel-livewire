<?php

namespace App\Livewire\RegistrarEntrada;

use App\Models\Estudiante;
use App\Models\Qr;
use App\Models\AuditoriaEntrada;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $estudiante;
    public $mensaje = '';
    public $tipoMensaje = '';

    public function updatedSearch($value)
    {
        $this->reset(['estudiante', 'mensaje', 'tipoMensaje']);

        if (empty($value)) {
            return;
        }

        // Buscar por UUID de QR o por identificación de estudiante
        $this->estudiante = Estudiante::with('carrera', 'qr')
            ->whereHas('qr', function ($query) use ($value) {
                $query->where('code', $value);
            })
            ->orWhere('identificacion', $value)
            ->first();

        if (!$this->estudiante) {
            $this->tipoMensaje = 'error';
            $this->mensaje = 'No se encontró ningún estudiante con el código o identificación proporcionado.';
        }
    }

    public function registrarEntrada()
    {
        if (!$this->estudiante || !$this->estudiante->qr) {
            $this->tipoMensaje = 'error';
            $this->mensaje = 'No se puede registrar la entrada: estudiante o QR no válido.';
            return;
        }

        if ($this->estudiante->qr->numero_de_entradas <= 0) {
            $this->tipoMensaje = 'error';
            $this->mensaje = 'Este código QR ya no tiene entradas disponibles.';
            return;
        }

        try {
            DB::transaction(function () {
                // Decrementar el número de entradas
                $this->estudiante->qr->decrement('numero_de_entradas');

                // Crear registro en la auditoría
                AuditoriaEntrada::create([
                    'qr_id' => $this->estudiante->qr->id,
                    'identificacion_estudiante' => $this->estudiante->identificacion,
                ]);
            });

            $this->tipoMensaje = 'success';
            $this->mensaje = '¡Entrada registrada con éxito!';
            
            // Refrescar los datos del estudiante para mostrar el número actualizado de entradas
            $this->estudiante->refresh();

        } catch (\Exception $e) {
            $this->tipoMensaje = 'error';
            $this->mensaje = 'Ocurrió un error al registrar la entrada. Por favor, inténtelo de nuevo.';
            // Opcional: registrar el error $e->getMessage()
        }
    }

    public function render()
    {
        return view('livewire.registrar-entrada.index');
    }
}
