<?php

use App\Livewire\Product\Create;
use App\Livewire\Product\Index;
use App\Livewire\Product\Update;
use App\Livewire\Estudiantes\Index as EstudiantesIndex;
use App\Livewire\Qrs\Index as QrsIndex;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    $data = \App\Models\Estudiante::join('carreras', 'estudiantes.carrera_id', '=', 'carreras.id')
        ->select('carreras.nombre as carrera', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('carreras.nombre')
        ->orderBy('total', 'desc')
        ->get();

    $chartData = [
        'labels' => $data->pluck('carrera'),
        'values' => $data->pluck('total'),
    ];

    return view('dashboard', ['chartData' => $chartData]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('products', Index::class)->name('products.index');
Route::get('products/create', Create::class)->name('products.create');
Route::get('products/{product}/edit', Update::class)->name('products.edit');

Route::get('estudiantes', EstudiantesIndex::class)->name('estudiantes.index');

Route::get('qrs', QrsIndex::class)->name('qrs.index');


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
