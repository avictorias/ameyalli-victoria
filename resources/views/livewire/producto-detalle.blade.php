<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
        
        <!-- Imagen del Producto -->
        <div>
            @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-80 object-cover rounded-xl shadow">
            @else
                <div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-400 rounded-xl">
                    Sin imagen disponible
                </div>
            @endif
        </div>

        <!-- Información del Producto -->
        <div class="flex flex-col justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-bold bg-gray-100 px-2.5 py-1 rounded-md">
                    SKU: {{ $producto->sku }}
                </span>
                
                <h1 class="text-3xl font-extrabold text-gray-900 mt-3">{{ $producto->nombre }}</h1>
                
                <div class="mt-4">
                    <p class="text-2xl font-bold text-green-600">${{ number_format($producto->precio_mxn, 2) }} MXN</p>
                    <p class="text-sm text-gray-500">${{ number_format($producto->precio_usd, 2) }} USD</p>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Descripción</h3>
                    <p class="text-gray-600 mt-2 text-sm leading-relaxed">
                        {{ $producto->descripcion ?? 'Sin descripción detallada para este producto.' }}
                    </p>
                </div>

                <div class="mt-6 text-sm text-gray-600">
                    <span class="font-semibold">Stock disponible:</span> {{ $producto->stock }} unidades
                </div>
            </div>

            <!-- Acciones de Compra -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center gap-4">
                <div class="w-1/3">
                    <input type="number" wire:model="cantidad" min="1" max="{{ $producto->stock }}" class="w-full border rounded-lg px-3 py-2 text-center font-bold">
                </div>

                <button wire:click="agregarAlCarrito" class="w-2/3 bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition shadow">
                    Añadir al Carrito
                </button>
            </div>

            <!-- Mensajes Flash -->
            @if (session()->has('message'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('tienda') }}" class="text-blue-600 hover:underline text-sm font-semibold">
            &larr; Volver a la tienda
        </a>
    </div>
</div>