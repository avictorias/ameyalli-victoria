<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Filtros dinámicos reactivos -->
    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o SKU..." class="border rounded-lg px-4 py-2 w-full md:w-1/3 shadow-sm">

        <select wire:model.live="ordenPrecio" class="border rounded-lg px-4 py-2 w-full md:w-1/4 shadow-sm">
            <option value="">Ordenar por precio</option>
            <option value="asc">Menor a Mayor Precio (MXN)</option>
            <option value="desc">Mayor a Menor Precio (MXN)</option>
        </select>
    </div>

    <!-- Listado de Productos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($productos as $producto)
        <div class="bg-white border rounded-xl p-4 shadow-sm flex flex-col justify-between">
            <div>
                @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-40 w-full object-cover rounded-md mb-3">
                @endif
                <span class="text-xs text-gray-500 uppercase font-bold">{{ $producto->sku }}</span>
                <h3 class="font-bold text-lg text-gray-800 truncate">{{ $producto->nombre }}</h3>
                <p class="text-green-600 font-semibold mt-1">${{ number_format($producto->precio_mxn, 2) }} MXN</p>
                <p class="text-gray-500 text-sm">${{ number_format($producto->precio_usd, 2) }} USD</p>
            </div>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('producto.detalle', $producto->id) }}" class="bg-gray-200 text-gray-700 text-center px-3 py-2 rounded-lg text-sm w-1/2 hover:bg-gray-300">Ver</a>
                <button wire:click="addToCart({{ $producto->id }})" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm w-1/2 hover:bg-blue-700">
                    Comprar
                </button>
            </div>
        </div>
        @empty
        <p class="col-span-4 text-center text-gray-500 py-10">No se encontraron productos disponibles.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $productos->links() }}
    </div>
</div>