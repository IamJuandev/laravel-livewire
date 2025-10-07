<div class="container mx-auto px-4" x-data="{ modalIsOpen: false }" @close-modal.window="modalIsOpen = false">
    <h1 class="text-2xl font-bold my-4">Gestión de Códigos QR</h1>

    @if (session()->has('message'))
        <div x-data="{ open: true }" x-show="open" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="open = false">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    {{-- Edit Modal --}}
    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false"
        class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
            class="flex max-w-lg flex-col gap-4 overflow-hidden rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
            
            <form wire:submit.prevent="save">
                <!-- Dialog Header -->
                <div
                    class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
                    <h3 id="modalTitle"
                        class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">
                        Editar QR
                    </h3>
                    <button type="button" x-on:click="modalIsOpen = false" aria-label="close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                            stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Dialog Body -->
                <div class="px-4 py-2 space-y-4">
                    <p class="text-sm text-gray-600">Código QR (sólo lectura): <span class="font-mono font-bold">{{ $code }}</span></p>

                    <div>
                        <label for="edit_numero_de_entradas" class="block text-gray-700 text-sm font-bold mb-2">Nº Entradas:</label>
                        <input type="number" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit_numero_de_entradas" wire:model="edit_numero_de_entradas">
                        @error('edit_numero_de_entradas') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="edit_estudiante_id" class="block text-gray-700 text-sm font-bold mb-2">Asignar a Estudiante:</label>
                        <select id="edit_estudiante_id" wire:model="edit_estudiante_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Sin Asignar --</option>
                            @if($all_students)
                                @foreach($all_students as $student)
                                    <option value="{{ $student->id }}">{{ $student->nombres }} {{ $student->apellidos }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('edit_estudiante_id') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <!-- Dialog Footer -->
                <div
                    class="flex flex-col-reverse justify-end gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="whitespace-nowrap rounded-radius px-4 py-2 text-center text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark">Cancelar</button>
                    <button type="submit"
                        class="whitespace-nowrap rounded-radius bg-primary border border-primary dark:border-primary-dark px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Generation Form --}}
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-4">1. Generar Nuevos QRs</h2>
        <form wire:submit.prevent="generarQrs">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="numeroDeQrs" class="block text-gray-700 text-sm font-bold mb-2">Cantidad de QRs:</label>
                    <input type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroDeQrs" placeholder="Ej: 100" wire:model="numeroDeQrs">
                    @error('numeroDeQrs') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="numeroDeEntradas" class="block text-gray-700 text-sm font-bold mb-2">Nº Entradas por QR:</label>
                    <input type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numeroDeEntradas" placeholder="Ej: 1" wire:model="numeroDeEntradas">
                    @error('numeroDeEntradas') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="fechaDelEvento" class="block text-gray-700 text-sm font-bold mb-2">Fecha del Evento:</label>
                    <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="fechaDelEvento" wire:model="fechaDelEvento">
                    @error('fechaDelEvento') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="self-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Generar QRs
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Mass Assignment Form --}}
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-4">2. Asignación Masiva por Carrera</h2>
        <p class="text-gray-600 text-sm mb-4">Seleccione una carrera para asignar los QRs disponibles a los estudiantes de esa carrera que aún no tengan uno.</p>
        <form wire:submit.prevent="asignarPorCarrera">
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="carrera_seleccionada" class="block text-gray-700 text-sm font-bold mb-2">Carrera:</label>
                    <select wire:model="carrera_seleccionada" id="carrera_seleccionada" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Seleccione una carrera --</option>
                        @if($carreras_disponibles)
                            @foreach($carreras_disponibles as $carrera)
                                <option value="{{ $carrera }}">{{ $carrera }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('carrera_seleccionada') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    
                    <div wire:loading wire:target="carrera_seleccionada" class="text-sm text-gray-500 mt-2">Calculando...</div>
                    <div wire:loading.remove wire:target="carrera_seleccionada">
                        @if($carrera_seleccionada)
                        <p class="text-sm text-gray-600 mt-2">
                            Estudiantes en esta carrera sin QR: <span class="font-bold">{{ $student_count_for_selected_carrera }}</span>
                        </p>
                        @endif
                    </div>
                </div>
                <div class="self-end">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Asignar por Carrera
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Results Table --}}
    <div class="bg-white shadow-md rounded my-6">
        <h2 class="text-xl font-bold my-4 px-8 pt-6">Últimos QRs Generados</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Entradas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha del Evento</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante Asignado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($qrs as $qr)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset($qr->path) }}" alt="QR Code" class="w-16 h-16">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $qr->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $qr->numero_de_entradas }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $qr->fecha_del_evento }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($qr->estudiante)
                                {{ $qr->estudiante->nombres }} {{ $qr->estudiante->apellidos }}
                            @else
                                <span class="text-xs text-gray-500">Sin Asignar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button x-on:click="modalIsOpen = true; $wire.edit({{ $qr->id }})" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                            <button wire:click="delete({{ $qr->id }})" wire:confirm="¿Estás seguro de que quieres eliminar este QR?" class="text-red-600 hover:text-red-900 ml-4">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No se han generado códigos QR todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="my-4">
        {{ $qrs->links() }}
    </div>
</div>