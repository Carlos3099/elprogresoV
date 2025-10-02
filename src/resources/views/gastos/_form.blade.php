@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <!-- Fila 1: Fecha y Categoría -->
    <div class="bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-calendar-day text-blue-500 mr-2"></i>Fecha *
        </label>
        <input type="date" name="fecha" value="{{ old('fecha', $gasto->fecha ? $gasto->fecha->format('Y-m-d') : date('Y-m-d')) }}" 
               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
    </div>

    <div class="bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-tag text-green-500 mr-2"></i>Categoría *
        </label>
        <select name="categoria" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition" required>
            <option value="">Seleccione una categoría</option>
            @foreach($categorias as $key => $value)
                <option value="{{ $key }}" {{ old('categoria', $gasto->categoria) == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Fila 2: Método de Pago y Monto -->
    <div class="bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-credit-card text-purple-500 mr-2"></i>Método de Pago *
        </label>
        <select name="metodo_pago" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" required>
            <option value="">Seleccione método</option>
            @foreach($metodosPago as $key => $value)
                <option value="{{ $key }}" {{ old('metodo_pago', $gasto->metodo_pago) == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i>Monto *
        </label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium">$</span>
            <input type="number" step="0.01" name="monto" value="{{ old('monto', $gasto->monto) }}" 
                   class="w-full border border-gray-300 rounded-lg p-3 pl-8 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition" 
                   min="0" required placeholder="0.00">
        </div>
    </div>

    <!-- Fila 3: Descripción (ancho completo) -->
    <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-file-alt text-red-500 mr-2"></i>Descripción *
        </label>
        <input name="descripcion" value="{{ old('descripcion', $gasto->descripcion) }}" 
               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" 
               required placeholder="Describe el gasto realizado">
    </div>

    <!-- Fila 4: Comprobante (ancho completo) -->
<!--     <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl">
        <label class="block text-sm font-medium mb-2 text-gray-700">
            <i class="fas fa-file-upload text-indigo-500 mr-2"></i>Comprobante
        </label>
        <div class="flex items-center space-x-3">
            <input type="file" name="comprobante" 
                   class="flex-1 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                   accept=".jpg,.jpeg,.png,.pdf">
            
            @if($gasto->comprobante_url)
            <div class="flex items-center space-x-2 bg-green-100 px-3 py-2 rounded-lg">
                <i class="fas fa-file-pdf text-green-600"></i>
                <span class="text-sm text-green-700">Comprobante cargado</span>
                <a href="{{ Storage::url($gasto->comprobante_url) }}" target="_blank" 
                   class="text-blue-600 hover:text-blue-800 ml-2" title="Descargar">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            @endif
        </div>
        <p class="text-xs text-gray-500 mt-2">Formatos permitidos: JPG, PNG, PDF (Máx. 2MB)</p>
    </div>  -->
</div>

<!-- Estilos para mejorar la apariencia -->
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