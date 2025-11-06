<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Carga de Datos Académicos</h2>

    <input type="file" wire:model="archivo" accept=".csv,.xlsx" class="border p-2 rounded">
    <button wire:click="procesar" class="bg-blue-600 text-white px-4 py-2 rounded ml-2 hover:bg-blue-700">
        Procesar Archivo
    </button>
    <div wire:loading class="text-blue-600 mt-2">Procesando...</div>

    @if($errores)
        <div class="mt-6 bg-red-50 p-4 rounded border">
            <h3 class="font-bold text-red-700">Errores:</h3>
            <table class="min-w-full mt-2 border">
                <thead class="bg-red-100">
                    <tr>
                        <th class="px-3 py-2 border">Fila</th>
                        <th class="px-3 py-2 border">Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($errores as $e)
                        <tr class="bg-red-50">
                            <td class="px-3 py-2 border">{{ $e['fila'] }}</td>
                            <td class="px-3 py-2 border">{{ $e['error'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6" id="grafico-container" wire:ignore>
        <canvas id="chartValidacion"></canvas>
    </div>

    <div class="mt-6 flex gap-4">
        <button wire:click="generarPDF" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
            Generar PDF
        </button>
        <button wire:click="enviarPorCorreo" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
            Enviar por Correo
        </button>
    </div>

    @if(session()->has('mensaje'))
        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('mensaje') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:load', () => {
        let chart = null;
        const initChart = (v = 0, e = 0) => {
            const ctx = document.getElementById('chartValidacion').getContext('2d');
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Válidos', 'Errores'],
                    datasets: [{
                        data: [v, e],
                        backgroundColor: ['#4CAF50', '#F44336']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: { display: true, text: 'Resultados de Validación' }
                    }
                }
            });
        };
        initChart();

        Livewire.on('carga-completada', (d) => {
            initChart(d.validos, d.errores);
            document.getElementById('grafico-container').insertAdjacentHTML('afterend',
                `<div class="mt-4 p-4 bg-blue-100 rounded"><strong>IA:</strong> Precisión de predicción: ${d.precision_ia}%</div>`
            );
        });
    });
</script>