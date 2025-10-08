<div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-lg dark:bg-gray-800 transform transition-all hover:scale-105">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Invitación de Grado</h1>
            <p class="text-gray-500 dark:text-gray-400">Estás cordialmente invitado a la ceremonia de graduación.</p>
        </div>

        <div class="p-6 border-t border-b border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Para:</p>
                <h2 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</h2>
                <p class="text-md text-gray-600 dark:text-gray-400">{{ $estudiante->carrera->nombre }}</p>
            </div>
        </div>

        <div class="text-center">
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Fecha del Evento:</p>
            @if ($estudiante->qr && $estudiante->qr->fecha_del_evento)
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($estudiante->qr->fecha_del_evento)->format('d F, Y') }}</p>
            @else
                <p class="text-xl font-bold text-red-500 dark:text-red-400">Fecha no definida</p>
            @endif
        </div>

        @if ($estudiante->qr)
        <div class="flex justify-center pt-4">
            <img src="{{ asset($estudiante->qr->path) }}" alt="QR Code" class="w-40 h-40 border-4 border-white dark:border-gray-700 rounded-lg shadow-md">
        </div>
        @endif
    </div>
</div>
