<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CarritoComponent extends Component
{
    protected CartService $cartService;

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function incrementar($id, $stockActual, $cantidadActual)
    {
        if ($cantidadActual + 1 > $stockActual) {
            session()->flash('error', 'Has alcanzado el límite del stock disponible.');
            return;
        }
        
        $this->cartService->updateQuantity($id, $cantidadActual + 1);
    }

    public function decrementar($id, $cantidadActual)
    {
        $this->cartService->updateQuantity($id, $cantidadActual - 1);
    }

    public function eliminar($id)
    {
        $this->cartService->remove($id);
        session()->flash('message', 'Producto eliminado del carrito.');
    }

    public function render()
    {
        return view('livewire.carrito-component', [
            'cartItems' => $this->cartService->getCart(),
            'totals' => $this->cartService->getTotals(),
        ])->layout('layouts.app');
    }
}