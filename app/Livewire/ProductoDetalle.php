<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Services\CartService;
use Livewire\Component;

class ProductoDetalle extends Component
{
    public Producto $producto;
    public $cantidad = 1;

    public function mount($id)
    {
        $this->producto = Producto::where('activo', 1)
            ->where('fecha_vigencia', '>=', now())
            ->findOrFail($id);
    }

   

    public function agregarAlCarrito(CartService $cartService)
    {
        $resultado = $cartService->add($this->producto, $this->cantidad);

        if (!$resultado['success']) {
            session()->flash('error', $resultado['message']);
            return;
        }

        // Redirige al carrito después de añadir con éxito
        return redirect()->route('carrito');
    }

    public function render()
    {
        return view('livewire.producto-detalle')->layout('layouts.app');
    }
}
