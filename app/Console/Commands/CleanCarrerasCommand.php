<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Estudiante;

class CleanCarrerasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-carreras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia y estandariza los nombres de las carreras en la tabla de estudiantes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpieza de nombres de carreras...');
        
        $estudiantes = Estudiante::all();
        $actualizados = 0;

        foreach ($estudiantes as $estudiante) {
            $original = $estudiante->carrera;
            if (empty($original)) continue;

            $normalizada = strtolower(trim($original));
            $corregida = $original;

            switch ($normalizada) {
                case 'ingenieria de sistemas':
                case 'ingeniera en sistemas':
                    $corregida = 'Ingeniería de Sistemas';
                    break;
                case 'administracion de empresas':
                    $corregida = 'Administración de Empresas';
                    break;
                default:
                    $corregida = ucwords($normalizada);
                    break;
            }
            
            if ($corregida !== $original) {
                $estudiante->carrera = $corregida;
                $estudiante->save();
                $actualizados++;
            }
        }

        $this->info("Limpieza completada. Registros actualizados: " . $actualizados);
        return 0;
    }
}