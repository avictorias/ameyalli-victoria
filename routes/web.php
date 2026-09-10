<?php


use Illuminate\Support\Facades\Route;
use App\Livewire\AdminProductos; 

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/productos', AdminProductos::class)->name('productos');
});