<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaEntrada extends Model
{
    use HasFactory;

    protected $table = 'auditoria_entradas';

    protected $fillable = [
        'qr_id',
        'identificacion_estudiante',
    ];
}