<div class="container mx-auto px-4 py-10">
    <div class="max-w-2xl mx-auto">

        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Registro de Entradas al Evento</h1>

        {{-- Search Input --}}
        <div class="relative mb-6">
            <input 
                type="text" 
                wire:model.live.debounce.500ms="search"
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Buscar por Nº de Identificación o escanear QR..."
                autofocus
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        {{-- Loading State --}}
        <div wire:loading class="text-center text-gray-500 dark:text-gray-400">
            Buscando...
        </div>

        {{-- Message Display --}}
        @if ($mensaje)
            <div 
                @class([
                    'p-4 rounded-lg text-center my-6',
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $tipoMensaje === 'success',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $tipoMensaje === 'error',
                ])
            >
                {{ $mensaje }}
            </div>
        @endif

        {{-- Student Card --}}
        @if ($estudiante)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform transform hover:-translate-y-1" wire:key="{{ $estudiante->id }}">
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-16 w-16 rounded-full border-2 border-indigo-500" src="https://ui-avatars.com/api/?name={{ urlencode($estudiante->nombres) }}&color=7F9CF5&background=EBF4FF" alt="">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $estudiante->carrera->nombre }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $estudiante->identificacion }}</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código QR</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $estudiante->qr->code ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-1 text-right">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Entradas Restantes</dt>
                                <dd class="mt-1 text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $estudiante->qr->numero_de_entradas ?? 0 }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50">
                    @if ($estudiante->qr && $estudiante->qr->numero_de_entradas > 0)
                        <button 
                            wire:click="registrarEntrada"
                            wire:loading.attr="disabled"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-colors duration-300 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="registrarEntrada">Registrar Entrada</span>
                            <span wire:loading wire:target="registrarEntrada">Registrando...</span>
                        </button>
                    @else
                        <button 
                            class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed"
                            disabled
                        >
                            No hay entradas disponibles
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
