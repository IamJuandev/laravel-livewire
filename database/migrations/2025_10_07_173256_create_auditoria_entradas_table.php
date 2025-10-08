<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auditoria_entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_id')->constrained('qrs')->onDelete('cascade');
            $table->string('identificacion_estudiante');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_entradas');
    }
};