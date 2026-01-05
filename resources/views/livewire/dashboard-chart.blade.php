{{-- Usamos dark:bg-neutral-900 con un borde sutil para eliminar el cuadro azul --}}
<div class="w-full p-6 bg-white border border-neutral-200 shadow-lg rounded-2xl dark:bg-neutral-900 dark:border-neutral-800 dark:shadow-none">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                Top Patologías ({{ count($labels) }})
            </h3>
        </div>
    </div>

    <div class="relative w-full pr-2 overflow-y-auto max-h-[400px] scrollbar-thin scrollbar-thumb-neutral-300 dark:scrollbar-thumb-neutral-700">

        <div style="height: {{ max(300, count($labels) * 35) }}px; position: relative;"
             wire:ignore
             x-data="{
                chart: null,
                init() {
                    let isDark = document.documentElement.classList.contains('dark');
                    // Usamos colores neutros (sin azul) para el texto
                    let textColor = isDark ? '#a3a3a3' : '#525252';
                    let gridColor = isDark ? '#262626' : '#e5e5e5';

                    this.chart = new Chart(this.$refs.canvas, {
                        type: 'bar',
                        data: {
                            labels: @js($labels),
                            datasets: [{
                                label: 'Pacientes',
                                data: @js($data),
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(139, 92, 246, 0.8)',
                                    'rgba(236, 72, 153, 0.8)',
                                    'rgba(20, 184, 166, 0.8)'
                                ],
                                borderRadius: 4,
                                barPercentage: 0.6,
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: { display: false },
                                    ticks: { color: textColor }
                                },
                                y: {
                                    grid: { color: gridColor }, // Línea de grilla neutra
                                    ticks: {
                                        color: textColor,
                                        autoSkip: false,
                                        font: { size: 11, weight: '500' }
                                    }
                                }
                            }
                        }
                    });
                }
             }">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>
</div>
