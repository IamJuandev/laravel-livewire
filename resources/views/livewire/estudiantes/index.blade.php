<div class="container mx-auto px-4 py-6" x-data="{ modalIsOpen: false }" @close-modal.window="modalIsOpen = false">
    <h1 class="text-2xl font-bold mb-6">Lista de Estudiantes</h1>

    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
    @endif

    <!-- Controls Container -->
    <div class="mb-6 space-y-4">
        <!-- Search and Pagination Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1">
                <!-- Search Input -->
                <div class="relative w-full sm:max-w-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" aria-hidden="true"
                        class="absolute left-3 top-1/2 size-5 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="search" wire:model.live.debounce.300ms="search"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        placeholder="Buscar estudiantes...">
                </div>

                <!-- Per Page Selector -->
                <div class="flex items-center gap-2 text-sm">
                    <label for="perPage" class="font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Por
                        Página</label>
                    <select wire:model.live='perPage' id="perPage" name="perPage"
                        class="w-20 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button x-on:click="modalIsOpen = true; $wire.create()" type="button"
                    class="whitespace-nowrap rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Crear estudiante
                </button>
                <button wire:click="export" type="button"
                    class="whitespace-nowrap rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Exportar a Excel
                </button>
            </div>
        </div>

        <!-- Import Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Importar Estudiantes</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Sube un archivo Excel (.xlsx, .xls) con las columnas: <strong>nombres, apellidos, identificacion,
                    carrera_nombre</strong>.
            </p>
            <form wire:submit.prevent="importar">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="file" wire:model="archivo_importacion"
                        class="flex-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <button type="submit"
                        class="whitespace-nowrap rounded-lg bg-purple-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <span wire:loading.remove wire:target="importar">Importar</span>
                        <span wire:loading wire:target="importar">Importando...</span>
                    </button>
                </div>
                @error('archivo_importacion')
                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" role="dialog"
        aria-modal="true" aria-labelledby="modalTitle">
        <div x-show="modalIsOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">

            <form wire:submit.prevent="save">
                <!-- Dialog Header -->
                <div
                    class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                        @if (isset($student_id)) Editar Estudiante @else Crear Nuevo Estudiante @endif
                    </h3>
                    <button type="button" x-on:click="modalIsOpen = false" aria-label="close modal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                            stroke-width="2" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Dialog Body -->
                <div class="px-6 py-5 space-y-4 max-h-[60vh] overflow-y-auto">
                    <div>
                        <x-form.imput label="Nombres del estudiante" name="nombres" placeholder="Nombres del estudiante"
                            wire:model="nombres" />
                        @error('nombres') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <x-form.imput label="Apellidos del estudiante" name="apellidos"
                            placeholder="Apellidos del estudiante" wire:model="apellidos" />
                        @error('apellidos') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <x-form.imput label="Identificación" name="identificacion"
                            placeholder="Identificación del estudiante" wire:model="identificacion" />
                        @error('identificacion') <span class="text-red-500 text-sm mt-1 block">{{ $message
                            }}</span>@enderror
                    </div>

                    <div>
                        <label for="carrera_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Carrera</label>
                        <select id="carrera_id" wire:model="carrera_id"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Seleccione una Carrera</option>
                            @if($all_carreras)
                            @foreach($all_carreras as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('carrera_id') <span class="text-red-500 text-sm mt-1 block">{{ $message
                            }}</span>@enderror
                    </div>
                </div>

                <!-- Dialog Footer -->
                <div
                    class="flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div
        class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                            wire:click="sortBy('nombres')">
                            <x-ui.short-button label="Nombres" field="nombres" :sortBy="$sortField"
                                :sortDirection="$sortDirection" />
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                            wire:click="sortBy('apellidos')">
                            <x-ui.short-button label="Apellidos" field="apellidos" :sortBy="$sortField"
                                :sortDirection="$sortDirection" />
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                            wire:click="sortBy('identificacion')">
                            <x-ui.short-button label="Identificación" field="identificacion" :sortBy="$sortField"
                                :sortDirection="$sortDirection" />
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                            wire:click="sortBy('carrera_id')">
                            <x-ui.short-button label="Carrera" field="carrera_id" :sortBy="$sortField"
                                :sortDirection="$sortDirection" />
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($estudiantes as $estudiante)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $estudiante->nombres }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $estudiante->apellidos }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $estudiante->identificacion }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $estudiante->carrera->nombre ?? 'Sin Asignar' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button x-on:click="modalIsOpen = true; $wire.edit({{ $estudiante->id }})"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                Editar
                            </button>
                            <button wire:click="delete({{ $estudiante->id }})"
                                wire:confirm="¿Estás seguro de que quieres eliminar a este estudiante?"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 ml-4 transition">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $estudiantes->links() }}
    </div>
</div>