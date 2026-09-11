<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\CurrencyService;
use Livewire\Component;

class CheckoutComponent extends Component
{
    public $correo_comprador;
    public $numero_tarjeta;
    public $pedidoExitoso = false;

    public function procesarPago(CartService $cartService)
    {
        // 1. Validar el correo obligatorio y los datos de la tarjeta simulada
        $this->validate([
            'correo_comprador' => 'required|email',
            'numero_tarjeta' => 'required|digits:16',
        ]);

        $totals = $cartService->getTotals();

        if (empty($cartService->getCart())) {
            session()->flash('error', 'El carrito está vacío.');
            return;
        }

        // 2. Simulación de validación bancaria / API Externa
        // (Aquí puedes validar si la tarjeta cumple con un número de prueba)
        $pagoAprobado = true; // Simulamos que el banco aprobó la transacción

        if ($pagoAprobado) {
            $this->pedidoExitoso = true;
            
            // Vaciar el carrito tras el éxito
            $cartService->clear();
            
            session()->flash('message', '¡Pedido confirmado y pago procesado con éxito!');
        } else {
            session()->flash('error', 'El banco rechazó la transacción. Intenta con otra tarjeta.');
        }
    }

    public function render(CartService $cartService)
    {
        return view('livewire.checkout-component', [
            'cartItems' => $cartService->getCart(),
            'totals' => $cartService->getTotals(),
        ])->layout('layouts.app');
    }
}