<?php

namespace App\Livewire;

use App\Models\Pedido;
use App\Models\Pedido_detalle;
use App\Services\CartService;
use App\Services\CurrencyService;
use App\Models\Producto;
use Carbon\Carbon;
use Livewire\Component;
use Str;

class CheckoutComponent extends Component
{
    public $correo_comprador;
    public $numero_tarjeta;
    public $pedidoExitoso = false;

    public function procesarPago(CartService $cartService)
    {
        $this->validate([
            'correo_comprador' => 'required|email',
            'numero_tarjeta' => 'required|digits:16',
        ]);
        $tarjetaValida = '9999999999999999';
        if ($this->numero_tarjeta !== $tarjetaValida) {
            session()->flash('error', 'La transacción fue rechazada: El número de tarjeta es inválido');
            return;
        }

        $cart = $cartService->getCart();
        if (empty($cart)) {
            session()->flash('error', 'El carrito está vacío.');
            return;
        }

        // 1. Simulación de validación bancaria
        $pagoAprobado = true;

        if ($pagoAprobado) {
            $totals = $cartService->getTotals();

            // 1. REGISTRAR EN LA TABLA DE PEDIDOS (Cabecera)
            $pedido = Pedido::create([
                'folio' => 'PED-' . strtoupper(Str::random(8)), // Genera un folio único aleatorio
                'correo' => $this->correo_comprador,
                'subtotal' => $totals['subtotal_mxn'],
                'impuestos' => $totals['iva_mxn'], // IVA del 16%
                'total' => $totals['total_mxn'],
                'estatus' => 'Completado',
                'fecha' => Carbon::now('America/Mexico_City'),
            ]);

            // 2. REGISTRAR EN PEDIDO DETALLE Y DESCONTAR STOCK
            foreach ($cart as $item) {
                Pedido_detalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_usd' => $item['precio_usd'],
                    'precio_mxn' => $item['precio_mxn'],
                ]);

                // Descontar stock...
                $producto = Producto::find($item['id']);
                if ($producto) {
                    $producto->update(['stock' => max(0, $producto->stock - $item['cantidad'])]);
                }
            }

            $this->pedidoExitoso = true;

            // Vaciar el carrito tras confirmar el pedido
            $cartService->clear();

            session()->flash('message', '¡Pago procesado con éxito y guardado en pedidos!');
        } else {
            session()->flash('error', 'La transacción fue rechazada por el banco.');
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
