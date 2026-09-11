<?php


use App\Livewire\CheckoutComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\AdminProductos;
use App\Livewire\CarritoComponent;
use App\Livewire\ProductoDetalle;
use App\Livewire\ReportesComponent;
use App\Livewire\TiendaCatalogo;
use Illuminate\Support\Facades\Auth;

// Redirección inicial opcional o ruta principal hacia el catálogo público
Route::get('/', TiendaCatalogo::class)->name('tienda');
Route::get('/dashboard', TiendaCatalogo::class)->name('dashboard');
Route::get('/tienda', TiendaCatalogo::class)->name('tienda');

// Vista de Detalle de Producto (Recibe el ID del producto)
Route::get('/producto/{id}', ProductoDetalle::class)->name('producto.detalle');

// Vista del Carrito de Compras
Route::get('/carrito', CarritoComponent::class)->name('carrito');

// Vista de Cotización / Checkout (Solicita correo y pasarela simulada)
Route::get('/checkout', CheckoutComponent::class)->name('checkout');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/productos', AdminProductos::class)->name('productos');
});
Route::get('/admin/reportes', ReportesComponent::class)->name('admin.reportes');


// Ruta temporal para evitar errores de logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/login', function () {
    return redirect('/admin/productos'); // O a donde prefieras redirigir el login
})->name('login');
require __DIR__.'/auth.php';