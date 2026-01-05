<div class="flex flex-col justify-center w-full h-full p-6 bg-white border border-neutral-200 shadow-lg rounded-2xl dark:bg-neutral-900 dark:border-neutral-800 dark:shadow-none">

    <h3 class="mb-6 text-lg font-bold text-center text-gray-800 dark:text-gray-100">
        Distribución por Edad
    </h3>

    <div class="relative w-full h-64"
         wire:ignore
         x-data="{
            chart: null,
            init() {
                let isDark = document.documentElement.classList.contains('dark');
                let textColor = isDark ? '#d4d4d4' : '#404040';

                this.chart = new Chart(this.$refs.canvas, {
                    type: 'doughnut',
                    data: {
                        labels: @js($labels),
                        datasets: [{
                            data: @js($data),
                            backgroundColor: [
                                '#10b981',
                                '#3b82f6',
                                '#f59e0b'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: textColor,
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
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
