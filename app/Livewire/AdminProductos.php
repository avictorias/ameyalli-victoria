<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // <-- 1. Importar el rasgo para subir archivos
use Illuminate\Support\Facades\Storage; // <-- Para manejar archivos guardados
use App\Services\CurrencyService;
class AdminProductos extends Component
{
    use WithPagination;
    use WithFileUploads; // <-- 2. Usarlo en el componente

    public $sku, $nombre, $descripcion_corta, $descripcion_larga, $precio_usd, $precio_mxn, $imagen, $stock, $fecha_vigencia, $activo = true;
    public $productoId;
    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'sku' => 'required|string|max:255|unique:producto,sku,' . $this->productoId,
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'descripcion_corta' => 'required|string|max:255', // <-- Ahora requerido
            'descripcion_larga' => 'required|string|max:255',
            'precio_usd' => 'required|numeric|gt:0',
            'precio_mxn' => 'required|numeric|gt:0',
           'imagen' => $this->productoId ? 'nullable|image|mimes:jpg,png|max:2048' : 'required|image|mimes:jpg,png|max:2048',
            'stock' => 'required|integer|min:0',
            'fecha_vigencia' => 'required|date',
            'activo' => 'boolean',
        ];
    }

    protected $messages = [
        'sku.required' => 'El SKU es requerido.',
        'nombre.required' => 'El nombre es requerido.',
        'nombre.regex' => 'El nombre no debe contener acentos ni caracteres especiales.',
        'descripcion_corta.required' => 'La descripción corta es requerida.',
        'descripcion_larga.required' => 'La descripción larga es requerida.',
        'precio_usd.gt' => 'El precio en USD debe ser mayor a 0.',
        'precio_usd.required' => 'El precio en USD es requerido.',
        'precio_mxn.gt' => 'El precio en MXN debe ser mayor a 0.',
        'precio_mxn.required' => 'El precio en MXN es requerido.',
        'stock.required' => 'El stock es requerido.',
        'imagen.image' => 'El archivo debe ser una imagen válida.',
        'imagen.mimes' => 'La imagen debe ser obligatoriamente de tipo JPG o PNG.',
        'imagen.max' => 'La imagen no debe pesar más de 2MB.',
        'imagen.required' => 'La imagen es requerida.',
        'fecha_vigencia.required' => 'La fecha de vigencia es requerida.',
    ];

    public function updated($propertyName)
    {
        
        $this->validateOnly($propertyName);
    }

  public function render()
    {
        return view('livewire.admin-productos', [
            'productos' => Producto::paginate(10)
        ])->layout('layouts.app'); // <-- Añade esta línea al final
    }

    public function create()
    {
        $this->resetFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetFields()
    {
        $this->sku = '';
        $this->nombre = '';
        $this->descripcion_corta = '';
        $this->descripcion_larga = '';
        $this->precio_usd = '';
        $this->precio_mxn = '';
        $this->imagen = null; // Se limpia la variable de archivo
        $this->stock = '';
        $this->fecha_vigencia = '';
        $this->activo = true;
        $this->productoId = null;
    }

    public function store()
    {
        $this->validate();

        $imagePath = null;
        // Si el usuario subió una imagen, la guardamos en storage/app/public/productos
        if ($this->imagen) {
            $imagePath = $this->imagen->store('productos', 'public');
        }

        Producto::create([
            'sku' => $this->sku,
            'nombre' => $this->nombre,
            'descripcion_corta' => $this->descripcion_corta,
            'descripcion_larga' => $this->descripcion_larga,
            'precio_usd' => $this->precio_usd,
            'precio_mxn' => $this->precio_mxn,
            'imagen' => $imagePath, // Guardamos la ruta del archivo en la BD
            'stock' => $this->stock,
            'fecha_vigencia' => $this->fecha_vigencia,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Producto creado exitosamente.');
        $this->closeModal();
        $this->resetFields();
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $this->productoId = $id;
        $this->sku = $producto->sku;
        $this->nombre = $producto->nombre;
        $this->descripcion_corta = $producto->descripcion_corta;
        $this->descripcion_larga = $producto->descripcion_larga;
        $this->precio_usd = $producto->precio_usd;
        $this->precio_mxn = $producto->precio_mxn;
        // Nota: No cargamos la ruta en la variable $imagen del formulario para evitar conflictos de tipo con el input file
        $this->imagen = null; 
        $this->stock = $producto->stock;
        $this->fecha_vigencia = $producto->fecha_vigencia;
        $this->activo = $producto->activo;

        $this->openModal();
    }

    public function update()
    {
        $this->validate();

        if ($this->productoId) {
            $producto = Producto::findOrFail($this->productoId);
            
            $imagePath = $producto->imagen; // Mantenemos la imagen actual por defecto

            // Si se subió una nueva imagen
            if ($this->imagen) {
                // Borramos la imagen anterior si existía para no saturar espacio
                if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                    Storage::disk('public')->delete($producto->imagen);
                }
                // Guardamos la nueva
                $imagePath = $this->imagen->store('productos', 'public');
            }

            $producto->update([
                'sku' => $this->sku,
                'nombre' => $this->nombre,
                'descripcion_corta' => $this->descripcion_corta,
                'descripcion_larga' => $this->descripcion_larga,
                'precio_usd' => $this->precio_usd,
                'precio_mxn' => $this->precio_mxn,
                'imagen' => $imagePath,
                'stock' => $this->stock,
                'fecha_vigencia' => $this->fecha_vigencia,
                'activo' => $this->activo,
            ]);

            session()->flash('message', 'Producto actualizado exitosamente.');
            $this->closeModal();
            $this->resetFields();
        }
    }

    public function delete($id)
    {
        Producto::findOrFail($id)->delete();
        session()->flash('message', 'Producto eliminado exitosamente.');
    }



    // Se ejecuta al escribir en el campo de USD y calcula el MXN usando el servicio (Redis / API)
    public function updatedPrecioUsd($value)
    {
        if (is_numeric($value) && $value > 0) {
            $currencyService = new CurrencyService();
            $rate = $currencyService->getUsdToMxnRate(); // Consulta Redis o la API externa
            
            // Calcula el equivalente en MXN redondeado a 2 decimales
            $this->precio_mxn = round($value * $rate, 2);
        } elseif (empty($value)) {
            $this->precio_mxn = '';
        }
    }

    // Se ejecuta al escribir en el campo de MXN y calcula el USD de forma inversa
    public function updatedPrecioMxn($value)
    {
        if (is_numeric($value) && $value > 0) {
            $currencyService = new CurrencyService();
            $rate = $currencyService->getUsdToMxnRate(); // Consulta Redis o la API externa
            
            if ($rate > 0) {
                // Calcula el equivalente en USD redondeado a 2 decimales
                $this->precio_usd = round($value / $rate, 2);
            }
        } elseif (empty($value)) {
            $this->precio_usd = '';
        }
    }
}