<?php

namespace App\Services;

use App\Models\Producto;

class CartService
{
    public function getCart()
    {
        return session()->get('cart', []);
    }

    public function add(Producto $producto, $cantidad = 1)
    {
        $cart = $this->getCart();
        $id = $producto->id;

        $cantidadActual = isset($cart[$id]) ? $cart[$id]['cantidad'] : 0;
        $nuevaCantidad = $cantidadActual + $cantidad;

        if ($nuevaCantidad > $producto->stock) {
            return [
                'success' => false,
                'message' => "No hay suficiente stock. Stock disponible: {$producto->stock}"
            ];
        }

        $cart[$id] = [
            'id' => $producto->id,
            'sku' => $producto->sku,
            'nombre' => $producto->nombre,
            'imagen' => $producto->imagen,
            'precio_usd' => $producto->precio_usd,
            'precio_mxn' => $producto->precio_mxn,
            'cantidad' => $nuevaCantidad,
            'stock' => $producto->stock,
        ];

        session()->put('cart', $cart);
        return ['success' => true, 'message' => 'Producto agregado al carrito con éxito.'];
    }

    public function updateQuantity($id, $cantidad)
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            if ($cantidad <= 0) {
                return $this->remove($id);
            }

            if ($cantidad > $cart[$id]['stock']) {
                return [
                    'success' => false,
                    'message' => "La cantidad solicitada supera el stock disponible ({$cart[$id]['stock']})."
                ];
            }

            $cart[$id]['cantidad'] = $cantidad;
            session()->put('cart', $cart);
        }

        return ['success' => true];
    }

    public function remove($id)
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return ['success' => true];
    }

    public function clear()
    {
        session()->forget('cart');
    }

    public function getTotals()
    {
        $cart = $this->getCart();
        $subtotalMxn = 0;
        $subtotalUsd = 0;

        foreach ($cart as $item) {
            $subtotalMxn += $item['precio_mxn'] * $item['cantidad'];
            $subtotalUsd += $item['precio_usd'] * $item['cantidad'];
        }

        $ivaMxn = $subtotalMxn * 0.16;
        $ivaUsd = $subtotalUsd * 0.16;

        return [
            'subtotal_mxn' => round($subtotalMxn, 2),
            'subtotal_usd' => round($subtotalUsd, 2),
            'iva_mxn' => round($ivaMxn, 2),
            'iva_usd' => round($ivaUsd, 2),
            'total_mxn' => round($subtotalMxn + $ivaMxn, 2),
            'total_usd' => round($subtotalUsd + $ivaUsd, 2),
        ];
    }
}