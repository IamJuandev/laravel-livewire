<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Invitaciones de Estudiantes</h1>

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
                            @if ($estudiante->qr)
                                <a href="{{ route('invitacion.show', $estudiante) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition" wire:navigate>
                                    Ver Invitación
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">Sin QR</span>
                            @endif
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
