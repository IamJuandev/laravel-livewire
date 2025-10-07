<div class="container mx-auto px-4" x-data="{ modalIsOpen: false }" @close-modal.window="modalIsOpen = false">
    <h1 class="text-2xl font-bold my-4">Lista de Estudiantes</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

            <div class="flex space-x-4 mb-4">
                <button x-on:click="modalIsOpen = true; $wire.create()" type="button"
                    class="whitespace-nowrap rounded-radius border border-primary dark:border-primary-dark bg-primary px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Crear
                    estudiante</button>
                <button wire:click="export" type="button"
                    class="whitespace-nowrap rounded-radius border border-green-600 dark:border-green-500 bg-green-600 px-4 py-2 text-center text-sm font-medium tracking-wide text-white transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 active:opacity-100 active:outline-offset-0 dark:bg-green-500 dark:text-on-primary-dark dark:focus-visible:outline-green-500">Exportar a Excel</button>
            </div>
    
            {{-- Import Form --}}
            <div class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Importar Estudiantes</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    Sube un archivo Excel (.xlsx, .xls) con las columnas: <strong>nombres, apellidos, identificacion, carrera_nombre</strong>.
                </p>
                <form wire:submit.prevent="importar">
                    <div class="flex items-center space-x-4">
                        <input type="file" wire:model="archivo_importacion" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <button type="submit"
                            class="whitespace-nowrap rounded-radius border border-purple-600 dark:border-purple-500 bg-purple-600 px-4 py-2 text-center text-sm font-medium tracking-wide text-white transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600 active:opacity-100 active:outline-offset-0 dark:bg-purple-500 dark:text-on-primary-dark dark:focus-visible:outline-purple-500">
                            <div wire:loading.remove wire:target="importar">
                                Importar
                            </div>
                            <div wire:loading wire:target="importar">
                                Importando...
                            </div>
                        </button>
                    </div>
                    @error('archivo_importacion') <span class="text-red-500 text-sm mt-2">{{ $message }}</span>@enderror
                </form>
            </div>
    {{-- Modal --}}
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
                        @if (isset($student_id)) Editar Estudiante @else Crear Nuevo Estudiante @endif
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
                    <x-form.imput label="Nombres del estudiante" name="nombres" placeholder="Nombres del estudiante" wire:model="nombres" />
                    @error('nombres') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror

                    <x-form.imput label="Apellidos del estudiante" name="apellidos" placeholder="Apellidos del estudiante" wire:model="apellidos" />
                    @error('apellidos') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror

                    <x-form.imput label="Identificación" name="identificacion" placeholder="Identificación del estudiante" wire:model="identificacion" />
                    @error('identificacion') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror

                    <div>
                        <label for="carrera_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carrera</label>
                        <select id="carrera_id" wire:model="carrera_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Seleccione una Carrera</option>
                            @if($all_carreras)
                                @foreach($all_carreras as $carrera)
                                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('carrera_id') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <!-- Dialog Footer -->
                <div
                    class="flex flex-col-reverse justify-end gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="whitespace-nowrap rounded-radius px-4 py-2 text-center text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark">Cancelar</button>
                    <button type="submit"
                        class="whitespace-nowrap rounded-radius bg-primary border border-primary dark:border-primary-dark px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombres
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Apellidos
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Identificación
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Carrera
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($estudiantes as $estudiante)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $estudiante->nombres }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $estudiante->apellidos }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $estudiante->identificacion }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $estudiante->carrera->nombre ?? 'Sin Asignar' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button x-on:click="modalIsOpen = true; $wire.edit({{ $estudiante->id }})" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                            <button wire:click="delete({{ $estudiante->id }})" wire:confirm="¿Estás seguro de que quieres eliminar a este estudiante?" class="text-red-600 hover:text-red-900 ml-4">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $estudiantes->links() }}
</div>