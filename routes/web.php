<?php

use App\Livewire\Product\Create;
use App\Livewire\Product\Index;
use App\Livewire\Product\Update;
use App\Livewire\Estudiantes\Index as EstudiantesIndex;
use App\Livewire\Qrs\Index as QrsIndex;
use App\Livewire\Invitacion\Show as InvitacionShow;
use App\Livewire\Invitaciones\Index as InvitacionesIndex;
use App\Livewire\RegistrarEntrada\Index as RegistrarEntradaIndex;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('dashboard', function () {
    // Datos para el gráfico de estudiantes (sin cambios)
    $studentChartData = \App\Models\Estudiante::join('carreras', 'estudiantes.carrera_id', '=', 'carreras.id')
        ->select('carreras.nombre as carrera', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('carreras.nombre')
        ->orderBy('total', 'desc')
        ->get()
        ->pipe(function ($data) {
            return [
                'labels' => $data->pluck('carrera'),
                'values' => $data->pluck('total'),
            ];
        });

    // Obtener fechas de eventos que tienen entradas registradas
    $eventDates = \App\Models\Qr::whereHas('auditoriaEntradas')
        ->distinct()
        ->orderBy('fecha_del_evento', 'desc')
        ->pluck('fecha_del_evento');

    // Determinar la fecha seleccionada (request o la más reciente)
    $selectedDate = request()->get('fecha_evento', $eventDates->first());

    // Datos para el gráfico de entradas
    $entriesData = \App\Models\AuditoriaEntrada::join('qrs', 'auditoria_entradas.qr_id', '=', 'qrs.id')
        ->where('qrs.fecha_del_evento', $selectedDate)
        ->select(\Illuminate\Support\Facades\DB::raw('DATE(auditoria_entradas.created_at) as fecha'), \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('fecha')
        ->orderBy('fecha', 'asc')
        ->get();

    $totalEntries = $entriesData->sum('total');

    return view('dashboard', [
        'studentChartData' => $studentChartData,
        'totalEntries' => $totalEntries,
        'eventDates' => $eventDates,
        'selectedDate' => $selectedDate,
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products', Index::class)->name('products.index');
    Route::get('products/create', Create::class)->name('products.create');
    Route::get('products/{product}/edit', Update::class)->name('products.edit');

    Route::get('estudiantes', EstudiantesIndex::class)->name('estudiantes.index');

    Route::get('qrs', QrsIndex::class)->name('qrs.index');

    Route::get('invitaciones', InvitacionesIndex::class)->name('invitaciones.index');
    Route::get('invitacion/{estudiante}', InvitacionShow::class)->name('invitacion.show');

    Route::get('registrar-entrada', RegistrarEntradaIndex::class)->name('registrar-entrada.index');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__ . '/auth.php';
