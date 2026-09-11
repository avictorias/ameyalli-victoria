<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Services\CartService;
use Livewire\Component;
use Livewire\WithPagination;


class TiendaCatalogo extends Component
{
    use WithPagination;

    public $search = '';
    public $ordenPrecio = '';

    // Resetea la paginación al cambiar los filtros de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Consulta aplicando los filtros requeridos: activos, vigentes y no eliminados
        $productos = Producto::where('activo', 1)
            ->where('fecha_vigencia', '>=', now())
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->when($this->ordenPrecio, function ($query) {
                $query->orderBy('precio_mxn', $this->ordenPrecio);
            })
            ->paginate(8);

        return view('livewire.tienda-catalogo', compact('productos'))
            ->layout('layouts.app'); // O tu layout público correspondiente
    }



    // Añade este método dentro de tu clase TiendaCatalogo:
    public function addToCart($id, CartService $cartService)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $resultado = $cartService->add($producto, 1);

            if (!$resultado['success']) {
                session()->flash('error', $resultado['message']);
                return;
            }

            // Redirige directo al carrito o muestra un mensaje de éxito
            return redirect()->route('carrito');
        }
    }
}
