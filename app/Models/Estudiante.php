<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Carrera;
use App\Models\Qr;

class Estudiante extends Model
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'apellidos',
        'identificacion',
        'carrera_id',
        'id_qr',
    ];

    /**
     * Get the qr associated with the student.
     */
    public function qr()
    {
        return $this->hasOne(Qr::class);
    }

    /**
     * Get the carrera that the student belongs to.
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
