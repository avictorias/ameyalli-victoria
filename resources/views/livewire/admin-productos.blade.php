<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Administración de Productos</h2>
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-semibold text-sm">
                    + Nuevo Producto
                </button>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Tabla de Productos -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio USD</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio MXN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Activo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse($productos as $producto)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->sku }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($producto->precio_usd, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($producto->precio_mxn, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $producto->activo == 1 ? 'Sí' : 'No' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="edit({{ $producto->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                    <button wire:click="delete({{ $producto->id }})"
                                        onclick="return confirm('¿Estás seguro de eliminar este producto?') || event.stopImmediatePropagation()"
                                        class="text-red-600 hover:text-red-900">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No hay productos
                                    registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $productos->links() }}
            </div>

            <!-- Modal para Crear / Editar -->
            @if($isModalOpen)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-6 w-full max-w-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $productoId ? 'Editar Producto' : 'Crear Nuevo Producto' }}
                        </h3>

                        <form wire:submit.prevent="{{ $productoId ? 'update' : 'store' }}">
                            <!-- SKU -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">SKU:</label>
                                <input type="text" wire:model.live="sku"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Nombre (Sin acentos ni caracteres especiales) -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                <input type="text" wire:model.live="nombre"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                                    placeholder="Ej: Camisa Polo">
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Descripción Corta -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción Corta:</label>
                                <input type="text" wire:model.live="descripcion_corta"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                @error('descripcion_corta') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Descripción Larga -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción Larga:</label>
                                <textarea wire:model.live="descripcion_larga"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"></textarea>
                                @error('descripcion_larga') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Precios (USD y MXN) -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Precio USD (> 0):</label>
                                    <input type="number" step="0.01" wire:model.live="precio_usd"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                    @error('precio_usd') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Precio MXN (> 0):</label>
                                    <input type="number" step="0.01" wire:model.live="precio_mxn"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                    @error('precio_mxn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Stock e Imagen -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Stock:</label>
                                    <input type="number" wire:model.live="stock"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                    @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                            </div>
                            <!-- Carga de Imagen -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Imagen del Producto:</label>
                                <input type="file" wire:model.live="imagen"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-white">

                                <!-- Spinner de carga en vivo mientras sube la foto -->
                                <div wire:loading wire:target="imagen" accept=".jpg, .jpeg, .png"
                                    class="text-blue-500 text-xs mt-1">Cargando imagen...</div>

                                <!-- Vista previa temporal de la imagen antes de guardar -->
                                @if ($imagen && is_object($imagen))
                                    <div class="mt-2">
                                        <span class="text-xs text-gray-500">Vista previa:</span>
                                        <img src="{{ $imagen->temporaryUrl() }}" class="h-16 w-16 object-cover rounded mt-1">
                                    </div>
                                @endif

                                @error('imagen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Fecha de Vigencia y Activo -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Vigencia:</label>
                                    <input type="date" wire:model.live="fecha_vigencia"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                    @error('fecha_vigencia') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex items-center mt-6">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="activo"
                                            class="form-checkbox h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 font-bold">Activo</span>
                                    </label>
                                    @error('activo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" wire:click="closeModal"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm">Cancelar</button>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>