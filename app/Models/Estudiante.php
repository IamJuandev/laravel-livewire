<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Carrera;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Qr;

class Estudiante extends Model
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory;

    #[Scope]
    protected function search(Builder $query, $value)
    {
        $query->where('nombres', 'like', '%' . $value . '%')
            ->orWhere('apellidos', 'like', '%' . $value . '%')
            ->orWhere('identificacion', 'like', '%' . $value . '%')
            ->orWhereHas('carrera', function (Builder $query) use ($value) {
                $query->where('nombre', 'like', '%' . $value . '%');
            });
    }

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
