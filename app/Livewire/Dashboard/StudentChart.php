<?php

namespace App\Livewire\Dashboard;

use App\Models\Estudiante;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentChart extends Component
{
    public $chartData;

    public function mount()
    {
        $data = Estudiante::select('carrera', DB::raw('count(*) as total'))
            ->groupBy('carrera')
            ->orderBy('total', 'desc')
            ->get();

        $labels = $data->pluck('carrera');
        $values = $data->pluck('total');

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nº de Estudiantes',
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    'borderWidth' => 1,
                    'data' => $values,
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.student-chart');
    }
}
