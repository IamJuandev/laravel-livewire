<div class="bg-white shadow-md rounded p-6" x-data="{
    chartData: @json($chartData),
    init() {
        const ctx = this.$refs.canvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: this.chartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
}" x-init="init()">

    <h2 class="text-xl font-bold mb-4">Estudiantes por Carrera</h2>

    <div wire:ignore>
        <canvas x-ref="canvas"></canvas>
    </div>
</div>