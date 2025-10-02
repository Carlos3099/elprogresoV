@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <!-- Fila 1: Nombre -->
    <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-tag text-green-500 mr-2"></i>Nombre *
        </label>
        <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" 
            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" 
            required placeholder="Nombre del producto">
    </div>

    <!-- Fila 2: Precio -->
    <div class="bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i>Precio *
        </label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium">$</span>
            <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" 
                   class="w-full border border-gray-300 rounded-lg p-3 pl-8 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition" 
                   min="0" required placeholder="0.00">
        </div>
    </div>

    <!-- Fila 3: Descripción -->
    <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-file-alt text-red-500 mr-2"></i>Descripción
        </label>
        <textarea name="descripcion" 
                  class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
                  rows="3" placeholder="Descripción del producto">{{ old('descripcion', $producto->descripcion) }}</textarea>
    </div>

    <!-- Fila 4: SKU (solo mostrar si ya existe el producto) -->
    @if($producto->exists)
    <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-barcode text-indigo-500 mr-2"></i>SKU
        </label>
        <input type="text" value="{{ $producto->sku }}" 
               class="w-full border border-gray-300 rounded-lg p-3 bg-gray-100 text-gray-700" 
               disabled>
    </div>
    @endif
</div>

<!-- Estilos -->
<style>
    .focus\:ring-2:focus {
        ring-width: 2px;
    }
    .transition {
        transition: all 0.2s ease-in-out;
    }
    .bg-gray-50 {
        background-color: #f9fafb;
    }
    .border-gray-300 {
        border-color: #d1d5db;
    }
</style>
