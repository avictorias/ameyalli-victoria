<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Carrito de Compras</h1>

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

    @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Listado de Productos -->
            <div class="md:col-span-2 space-y-4">
                @foreach($cartItems as $id => $item)
                    <div class="flex items-center justify-between bg-white p-4 border rounded-xl shadow-sm">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $item['nombre'] }}</h3>
                                <p class="text-xs text-gray-500">SKU: {{ $item['sku'] }}</p>
                                <p class="text-green-600 font-semibold text-sm mt-1">${{ number_format($item['precio_mxn'], 2) }} MXN</p>
                            </div>
                        </div>

                        <!-- Botones de Cantidad y Eliminar -->
                        <div class="flex items-center space-x-2">
                            <button wire:click="decrementar({{ $id }}, {{ $item['cantidad'] }})" class="bg-gray-100 border px-3 py-1 rounded-lg font-bold hover:bg-gray-200">-</button>
                            <span class="w-8 text-center font-bold text-sm">{{ $item['cantidad'] }}</span>
                            <button wire:click="incrementar({{ $id }}, {{ $item['stock'] }}, {{ $item['cantidad'] }})" class="bg-gray-100 border px-3 py-1 rounded-lg font-bold hover:bg-gray-200">+</button>
                            
                            <button wire:click="eliminar({{ $id }})" class="text-red-500 hover:text-red-700 ml-4 font-bold text-xs">Eliminar</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Resumen y Totales -->
            <div class="bg-white p-6 border rounded-xl shadow-sm h-fit">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Resumen de Cotización</h2>
                
                <div class="space-y-2 text-sm text-gray-600 mb-6">
                    <div class="flex justify-between">
                        <span>Subtotal MXN:</span>
                        <span>${{ number_format($totals['subtotal_mxn'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>IVA (16%) MXN:</span>
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

                <a href="{{ route('checkout') }}" class="block text-center bg-blue-600 text-white w-full py-3 rounded-lg font-bold hover:bg-blue-700 text-sm shadow">
                    Continuar al Checkout
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white border rounded-xl shadow-sm">
            <p class="text-gray-500 text-base mb-4">Tu carrito está vacío.</p>
            <a href="{{ route('catalogo') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm">Ver Catálogo</a>
        </div>
    @endif
</div>