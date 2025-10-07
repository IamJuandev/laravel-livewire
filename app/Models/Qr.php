<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'path',
        'numero_de_entradas',
        'fecha_del_evento',
        'estudiante_id',
    ];

    /**
     * Get the student that owns the QR code.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
