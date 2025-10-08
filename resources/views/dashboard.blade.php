<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Tarjeta de Entradas al Evento --}}
        <div class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Entradas al Evento</h2>

                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="flex items-center gap-2">
                        <label for="fecha_evento" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fecha:
                        </label>
                        <select name="fecha_evento" id="fecha_evento" onchange="this.form.submit()"
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach ($eventDates as $date)
                            <option value="{{ $date }}" @selected($date==$selectedDate)>
                                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div class="text-5xl font-bold text-center text-indigo-600 dark:text-indigo-400">
                {{ $totalEntries }}
            </div>
            <p class="text-center text-gray-600 dark:text-gray-400 mt-2">
                Total de entradas registradas
            </p>
        </div>

        {{-- Gráfico de Estudiantes por Carrera --}}
        <div class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                Estudiantes por Carrera
            </h2>

            {{-- Contenedor responsive para el gráfico --}}
            <div class="relative" style="height: 400px;">
                <canvas id="myStudentChart"></canvas>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        // Gráfico de Estudiantes por Carrera
            (function() {
                const studentChartData = @json($studentChartData);
                const ctx = document.getElementById('myStudentChart').getContext('2d');
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: studentChartData.labels,
                        datasets: [{
                            label: 'Nº de Estudiantes',
                            data: studentChartData.values,
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',   // Indigo
                                'rgba(236, 72, 153, 0.8)',   // Pink
                                'rgba(34, 197, 94, 0.8)',    // Green
                                'rgba(251, 146, 60, 0.8)',   // Orange
                                'rgba(59, 130, 246, 0.8)',   // Blue
                                'rgba(168, 85, 247, 0.8)'    // Purple
                            ],
                            borderColor: [
                                'rgba(99, 102, 241, 1)',
                                'rgba(236, 72, 153, 1)',
                                'rgba(34, 197, 94, 1)',
                                'rgba(251, 146, 60, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(168, 85, 247, 1)'
                            ],
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            })();
    </script>
    @endpush
</x-layouts.app>