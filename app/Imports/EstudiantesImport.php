<?php

namespace App\Imports;

use App\Models\Estudiante;
use App\Models\Carrera;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstudiantesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find or create the Carrera
        $carrera = Carrera::firstOrCreate(
            ['nombre' => $row['carrera_nombre']],
        );

        return new Estudiante([
            'nombres'        => $row['nombres'],
            'apellidos'      => $row['apellidos'],
            'identificacion' => $row['identificacion'],
            'carrera_id'     => $carrera->id,
        ]);
    }
}