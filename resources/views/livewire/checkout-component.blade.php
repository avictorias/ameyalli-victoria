<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Cotización y Checkout</h1>

    @if(session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('message') }}
        </div>
    @endif

    @if($pedidoExitoso)
        <div class="bg-white border rounded-xl p-8 text-center shadow-sm">
            <h2 class="text-2xl font-bold text-green-600 mb-2">¡Pedido Generado Correctamente!</h2>
            <p class="text-gray-600 mb-6 text-sm">Hemos procesado tu pago de forma simulada y guardado los detalles de tu cotización.</p>
            <a href="{{ route('catalogo') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm">Volver al Catálogo</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Formulario de Datos y Simulación de Pago -->
            <div class="bg-white p-6 border rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Datos de Facturación y Pago</h2>
                
                <form wire:submit.prevent="procesarPago">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Correo del Comprador *</label>
                        <input type="email" wire:model="correo_comprador" placeholder="correo@ejemplo.com" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @error('correo_comprador') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Simulación Tarjeta Bancaria (16 dígitos) *</label>
                        <input type="text" wire:model="numero_tarjeta" placeholder="4111222233334444" maxlength="16" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @error('numero_tarjeta') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition text-sm shadow">
                        Validar Pago y Generar Pedido
                    </button>
                </form>
            </div>

            <!-- Resumen de Productos, Subtotal, IVA (16%) y Total -->
            <div class="bg-white p-6 border rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Resumen de la Orden</h2>
                
                <div class="space-y-3 mb-6 max-h-48 overflow-y-auto pr-2">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>{{ $item['nombre'] }} (x{{ $item['cantidad'] }})</span>
                            <span class="font-semibold">${{ number_format($item['precio_mxn'] * $item['cantidad'], 2) }} MXN</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 text-sm border-t pt-4 text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal MXN:</span>
                        <span>${{ number_format($totals['subtotal_mxn'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>IVA (16%):</span>
                        <span>${{ number_format($totals['iva_mxn'], 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-base text-gray-900 border-t pt-2">
                        <span>Total MXN:</span>
                        <span>${{ number_format($totals['total_mxn'], 2) }} MXN</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 pt-2 border-t">
                        <span>Total USD (Referencia):</span>
                        <span>${{ number_format($totals['total_usd'], 2) }} USD</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>