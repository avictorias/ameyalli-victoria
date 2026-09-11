<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Módulo de Reportes y Estadísticas</h1>

    <!-- Filtro por Rango de Fechas -->
    <div class="bg-white p-6 border rounded-xl shadow-sm mb-8">
        <h2 class="text-lg font-bold mb-4">Filtrar Pedidos por Rango de Fechas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" wire:model.live="fechaInicio" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" wire:model.live="fechaFin" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <div class="bg-blue-50 p-2.5 rounded-lg border border-blue-200 text-center">
                    <span class="text-xs text-blue-600 font-bold block">Ganancia en el Periodo:</span>
                    <span class="text-lg font-extrabold text-blue-800">${{ number_format($gananciaTotal, 2) }} MXN</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Gráfica de Ganancias Reactiva -->
        <div class="bg-white p-6 border rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 border-b pb-2">Gráfica de Ganancias por Periodo</h2>
            <div class="h-64 relative flex items-center justify-center" wire:ignore>
                <canvas id="gananciasChart"></canvas>
            </div>
        </div>

        <!-- Top 3 de Productos Más Vendidos -->
        <div class="bg-white p-6 border rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 border-b pb-2">Top 3 Productos Más Vendidos</h2>
            <div class="space-y-4">
                @forelse($topProductos as $index => $producto)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="bg-blue-600 text-white font-bold w-6 h-6 rounded-full flex items-center justify-center text-xs">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">{{ $producto->nombre }}</h3>
                                <p class="text-xs text-gray-500">Vendidos: {{ $producto->total_vendido ?? 0 }} unidades</p>
                            </div>
                        </div>
                        <span class="font-bold text-green-600 text-sm">${{ number_format($producto->precio_mxn, 2) }} MXN</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">No hay ventas registradas en este rango de fechas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let miGrafica = null;

    function renderizarGrafica(fechas, totales) {
        const ctx = document.getElementById('gananciasChart').getContext('2d');
        
        if (miGrafica) {
            miGrafica.destroy();
        }

        miGrafica = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Ganancias Diarias (MXN)',
                    data: totales,
                    backgroundColor: 'rgba(37, 99, 235, 0.6)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderizarGrafica(@json($graficaFechas), @json($graficaTotales));
    });

    // Actualizar la gráfica cada vez que Livewire termine de actualizar los datos
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('component-rendered', () => {
            // Se actualiza con los nuevos datos inyectados
        });
    });

    // Escuchar cambios de Livewire para refrescar Chart.js de forma nativa
    window.addEventListener('livewire:navigated', () => {
        renderizarGrafica(@json($graficaFechas), @json($graficaTotales));
    });
</script>
@endpush