<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Pedido_detalle;
use Livewire\Component;
use Carbon\Carbon;

class ReportesComponent extends Component
{
    public $fechaInicio;
    public $fechaFin;

    public function mount()
    {
        $this->fechaInicio = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->fechaFin = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $pedidosIds = Pedido::whereDate('fecha', '>=', $this->fechaInicio)
                            ->whereDate('fecha', '<=', $this->fechaFin)
                            ->pluck('id');

        $gananciaTotal = Pedido::whereIn('id', $pedidosIds)->sum('total');

        // Top 3 de productos más vendidos
        $topProductosIds = Pedido_detalle::whereIn('pedido_id', $pedidosIds)
            ->select('producto_id', \DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->take(3)
            ->pluck('producto_id');

        $topProductos = collect();
        if ($topProductosIds->isNotEmpty()) {
            $productosColeccion = Producto::whereIn('id', $topProductosIds)->get()->keyBy('id');
            foreach ($topProductosIds as $id) {
                if (isset($productosColeccion[$id])) {
                    $prod = $productosColeccion[$id];
                    $prod->total_vendido = Pedido_detalle::whereIn('pedido_id', $pedidosIds)
                        ->where('producto_id', $id)
                        ->sum('cantidad');
                    $topProductos->push($prod);
                }
            }
        }

        // Datos para la gráfica transformados a arrays nativos de PHP
        $gananciasPorDia = Pedido::whereIn('id', $pedidosIds)
            ->get()
            ->groupBy(function($pedido) {
                return Carbon::parse($pedido->fecha)->format('Y-m-d');
            })->map(function ($row) {
                return $row->sum('total');
            });

        return view('livewire.reportes-component', [
            'gananciaTotal' => $gananciaTotal,
            'topProductos' => $topProductos,
            'graficaFechas' => $gananciasPorDia->keys()->values()->toArray(),
            'graficaTotales' => $gananciasPorDia->values()->toArray(),
        ])->layout('layouts.app');

        
    }
}