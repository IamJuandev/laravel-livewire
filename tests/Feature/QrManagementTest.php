<?php

use App\Models\User;
use App\Models\Carrera;
use App\Models\Estudiante;
use App\Models\Qr;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can view qr management page', function () {
    $user = User::factory()->create();
    actingAs($user);

    $this->get(route('qrs.index'))->assertStatus(200);
});

test('can generate new qr codes', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test('qrs.index')
        ->set('numeroDeQrs', 10)
        ->set('numeroDeEntradas', 2)
        ->set('fechaDelEvento', '2025-12-31')
        ->call('generarQrs');

    $this->assertDatabaseCount('qrs', 10);
});

test('can assign qrs to students by career', function () {
    $user = User::factory()->create();
    actingAs($user);

    $carrera = Carrera::factory()->create();
    $estudiantes = Estudiante::factory()->count(5)->create(['carrera_id' => $carrera->id]);
    $qrs = Qr::factory()->count(5)->create(['estudiante_id' => null]);

    Livewire::test('qrs.index')
        ->set('carrera_seleccionada', $carrera->nombre)
        ->call('asignarPorCarrera');

    foreach ($estudiantes as $estudiante) {
        assertDatabaseHas('qrs', ['estudiante_id' => $estudiante->id]);
    }
});

