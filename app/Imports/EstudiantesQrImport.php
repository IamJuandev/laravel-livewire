<?php

namespace App\Imports;

use App\Models\Estudiante;
use App\Models\Qr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstudiantesQrImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if (empty($row['identificacion'])) {
                continue;
            }

            // Find the student by their identification number
            $estudiante = Estudiante::where('identificacion', $row['identificacion'])->first();
            
            // If student exists, find an unassigned QR code
            if ($estudiante) {
                // Check if this student already has a QR assigned to avoid duplicates
                $existingQr = Qr::where('estudiante_id', $estudiante->id)->exists();
                if ($existingQr) {
                    continue; // Skip if student already has a QR
                }

                $qr = Qr::whereNull('estudiante_id')->orderBy('id', 'asc')->first();

                // If an unassigned QR code is found, link it to the student
                if ($qr) {
                    $qr->estudiante_id = $estudiante->id;
                    $qr->save();
                }
            }
        }
    }
}
