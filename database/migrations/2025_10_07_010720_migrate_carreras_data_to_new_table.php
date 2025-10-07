<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Estudiante;
use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Get distinct career names from the old text column
        $distinctCarreras = Estudiante::select('carrera')->distinct()->whereNotNull('carrera')->pluck('carrera');

        // 2. Populate the new 'carreras' table
        foreach ($distinctCarreras as $carreraNombre) {
            Carrera::create(['nombre' => $carreraNombre]);
        }

        // 3. Add the new 'carrera_id' foreign key column to 'estudiantes'
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->onDelete('set null');
        });

        // 4. Update the 'carrera_id' for existing students
        $carreras = Carrera::all();
        foreach ($carreras as $carrera) {
            Estudiante::where('carrera', $carrera->nombre)->update(['carrera_id' => $carrera->id]);
        }

        // 5. Drop the old 'carrera' text column
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn('carrera');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add the old 'carrera' text column back
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('carrera')->nullable();
        });

        // 2. Repopulate the old 'carrera' column using a join
        $estudiantes = DB::table('estudiantes')->select('estudiantes.id', 'carreras.nombre as carrera_nombre')
            ->leftJoin('carreras', 'estudiantes.carrera_id', '=', 'carreras.id')->get();
        
        foreach ($estudiantes as $estudiante) {
            DB::table('estudiantes')->where('id', $estudiante->id)->update(['carrera' => $estudiante->carrera_nombre]);
        }

        // 3. Drop the foreign key column
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['carrera_id']);
            $table->dropColumn('carrera_id');
        });
    }
};