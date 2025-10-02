@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <style>
        .asterisco{
            color: red;
            font-weight: 700;
            font-size: 1.4em;
        }

           /* Estilo para inputs con icono de moneda */
        .currency-input & precio-input {
            padding-left: 3rem !important;
        }
        </style>
    <!-- Cliente -->
    <div >
        <label for="seleccionar_cliente" class="block text-sm font-medium mb-1">¿Seleccionar Cliente? </label>
        <input type="checkbox" id="seleccionar_cliente" class="h-6 w-6 mt-3 mb-0 border rounded-lg p-2">
    </div>
    <div id="cliente_container" class="hidden" >
        <label class="block text-sm font-medium mb-1">Cliente <span class="asterisco">*</span></label>
        <select name="cliente_id" id="cliente_select" class="w-full border rounded-lg p-2" required>
            <option value="">Seleccione un cliente</option>  
            <option value="0">Cliente anónimo</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ old('cliente_id', isset($venta) ? $venta->cliente_id : '') == $cliente->id ? 'selected' : '' }}>
                    {{ $cliente->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    
    <!-- Fecha -->
    <div>
        <label class="block text-sm font-medium mb-1">Fecha *</label>
        <input type="date" name="fecha" value="{{ old('fecha', isset($venta) && $venta->fecha ? $venta->fecha->format('Y-m-d') : date('Y-m-d')) }}" class="w-full border rounded-lg p-2" required>
    </div>

    <!-- ✅ SECCIÓN MODIFICADA: PAGOS MÚLTIPLES (siempre visible) -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Métodos de Pago  <span class="asterisco">*</span></label>
        <div id="pagos-container" class="space-y-3 mb-3">
            <!-- Pago en efectivo por defecto (siempre visible) -->
            <div class="pago-row grid grid-cols-1 md:grid-cols-12 gap-2 items-end border p-3 rounded-lg bg-gray-50">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Método  <span class="asterisco">*</span></label>
                    <select name="pagos[0][metodo]" class="pago-metodo w-full border rounded-lg p-2" required>
                        <option value="efectivo" selected>Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
                
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Monto <span class="asterisco">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" step="0.01" name="pagos[0][monto]" 
                            class="pago-monto w-full border rounded-lg p-2 pl-8" min="0.01" placeholder="0.00">
                    </div>
                </div>
                <!--
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Referencia</label>
                    <input type="text" name="pagos[0][referencia]" class="pago-referencia w-full border rounded-lg p-2">
                </div>
                -->
                <div class="md:col-span-2 pago-destinatario-container hidden">
                    <label class="block text-sm font-medium mb-1">Destinatario  <span class="asterisco">*</span></label>
                    <select name="pagos[0][destinatario]" class="pago-destinatario w-full border rounded-lg p-2">
                        <option value="">Seleccione</option>
                        <option value="Karen">Karen</option>
                        <option value="Ethan">Ethan</option>
                    </select>
                </div>
                
                <div class="md:col-span-1">
                    <button type="button" class="quitar-pago bg-red-500 text-white p-2 rounded-lg w-full" disabled>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2 mb-3">
            <span class="text-sm font-medium">Total pagado: </span>
            <span id="total_pagado" class="font-bold">$0.00</span>
            <span id="restante_pago" class="ml-4 text-sm"></span>
        </div>
        
        <button type="button" id="agregar-pago" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm">
            + Agregar Método de Pago
        </button>
        
        <!-- Campo oculto para el método de pago principal que se enviará al servidor -->
        <input type="hidden" name="metodo_pago" id="metodo_pago_input" value="efectivo">
    </div>
    
    <!-- Productos -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Productos  <span class="asterisco">*</span></label>
        <div id="productos-container" class="space-y-3">
            <!-- Los productos se agregarán dinámicamente aquí -->
        </div>
        <button type="button" id="agregar-producto" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded-lg text-sm">
            + Agregar Producto
        </button>
    </div>
    
    <!-- Campos de totales 
    <div>
        <label class="block text-sm font-medium mb-1">Subtotal</label>   -->
        <input type="hidden" step="0.01" name="subtotal" id="subtotal" value="0" class="w-full border rounded-lg p-2 bg-gray-100" readonly>
        <!-- El que está debajo se debe de usar cuando se usen descuentos, impuestos y toda la implementación de momento seguría comentado
        <input type="number" step="0.01" name="subtotal" id="subtotal" value="{{ old('subtotal', isset($venta) ? $venta->subtotal : 0) }}" class="w-full border rounded-lg p-2 bg-gray-100" readonly>
    </div>
    -->
    <!-- Campos ocultos para descuento e impuestos con valor 0 -->
    <input type="hidden" name="descuento" value="0">
    <input type="hidden" name="impuestos" value="0">

    <div>
        <label class="block text-sm font-medium mb-1">Total</label>
        <input type="number" step="0.01" name="total" id="total" value="{{ old('total', isset($venta) ? $venta->total : 0) }}" class="w-full border rounded-lg p-2 bg-gray-100" readonly>
    </div>
    
    <!-- Notas -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Notas</label>
        <textarea name="notas" rows="3" class="w-full border rounded-lg p-2">{{ old('notas', isset($venta) ? $venta->notas : '') }}</textarea>
    </div>
</div>

<!-- Template para fila de pago -->
<template id="pago-template">
    <div class="pago-row grid grid-cols-1 md:grid-cols-12 gap-2 items-end border p-3 rounded-lg bg-gray-50">
        <div class="md:col-span-3">
            <label class="block text-sm font-medium mb-1">Método  <span class="asterisco">*</span></label>
            <select name="pagos[INDEX][metodo]" class="pago-metodo w-full border rounded-lg p-2" required>
                <option value="">Seleccione método</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
        </div>
        
        <div class="md:col-span-3">
            <label class="block text-sm font-medium mb-1">Monto <span class="asterisco">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" step="0.01" name="pagos[INDEX][monto]" 
                        class="pago-monto w-full border rounded-lg p-2 pl-8" min="0.01" placeholder="0.00">
                </div>
        </div>
        <!--
        <div class="md:col-span-3">
            <label class="block text-sm font-medium mb-1">Referencia</label>
            <input type="text" name="pagos[INDEX][referencia]" class="pago-referencia w-full border rounded-lg p-2">
        </div>
         -->
        <div class="md:col-span-2 pago-destinatario-container hidden">
            <label class="block text-sm font-medium mb-1">Destinatario  <span class="asterisco">*</span></label>
            <select name="pagos[INDEX][destinatario]" class="pago-destinatario w-full border rounded-lg p-2">
                <option value="">Seleccione</option>
                <option value="Karen">Karen</option>
                <option value="Ethan">Ethan</option>
            </select>
        </div>
        
        <div class="md:col-span-1">
            <button type="button" class="quitar-pago bg-red-500 text-white p-2 rounded-lg w-full">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>
<!-- Template para fila de producto (usado por JavaScript) -->
<template id="producto-template">
    <div class="producto-row grid grid-cols-1 md:grid-cols-12 gap-2 items-end border p-3 rounded-lg">
        <div class="md:col-span-5">
            <label class="block text-sm font-medium mb-1">Producto</label>
            <select name="productos[INDEX][producto_id]" class="producto-select w-full border rounded-lg p-2" required>
                <option value="">Seleccione producto</option>
                @foreach($productos as $producto)
                    @php
                        $existencia = $producto->existencias->where('sucursal_id', Auth::user()->sucursal_id)->first();
                        $stock = $existencia ? $existencia->stock_actual : 0;
                    @endphp
                    <option value="{{ $producto->id }}" 
                            data-precio="{{ $producto->precio }}"
                            data-stock="{{ $stock }}"
                            {{ $stock == 0 ? 'disabled' : '' }}>
                        {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }} 
                        (Stock: {{ $stock }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Cantidad</label>
            <input type="number" name="productos[INDEX][cantidad]" class="cantidad-input w-full border rounded-lg p-2" min="1" value="1" required>
            <div class="stock-message text-xs text-red-600 mt-1 hidden"></div>
        </div>
        <div class="md:col-span-2">

            <label class="block text-sm font-medium mb-1">Precio Unitario</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                    <input type="number" step="0.01" name="productos[INDEX][precio_unitario]" class="precio-input w-full border rounded-lg p-2 pl-6" min="0" required>
                </div>  
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Total Línea</label>
            <input type="number" step="0.01" name="productos[INDEX][total_linea]" class="total-linea-input w-full border rounded-lg p-2 bg-gray-100" readonly>
        </div>
        <div class="md:col-span-1">
            <button type="button" class="quitar-producto bg-red-500 text-white p-2 rounded-lg w-full">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>



<!-- Contenedor oculto para pasar datos de PHP a JavaScript -->
<div id="venta-data" 
     data-productos-iniciales="{{ isset($venta) && $venta->detalles->count() > 0 ? json_encode($venta->detalles->map(function($detalle) {
         $existencia = $detalle->producto->existencias->where('sucursal_id', Auth::user()->sucursal_id)->first();
         $stock = $existencia ? $existencia->stock_actual : 0;
         return [
             'producto_id' => $detalle->producto_id,
             'cantidad' => $detalle->cantidad,
             'precio' => $detalle->precio_unitario,
             'total_linea' => $detalle->total_linea,
             'stock' => $stock
         ];
     })) : '[]' }}"
     data-pagos-iniciales="{{ isset($venta) && $venta->pagos->count() > 0 ? json_encode($venta->pagos->map(function($pago) {
         return [
             'metodo' => $pago->metodo_pago,
             'monto' => $pago->monto,
             'referencia' => $pago->referencia_pago,
             'destinatario' => $pago->destinatario_transferencia
         ];
     })) : '[]' }}"
     style="display: none;">
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productosContainer = document.getElementById('productos-container');
    const agregarProductoBtn = document.getElementById('agregar-producto');
    const productoTemplate = document.getElementById('producto-template');
    const ventaData = document.getElementById('venta-data');
    let productoIndex = 0;
    
    // === CONTROL DE SELECCIÓN DE CLIENTE ===
    const seleccionarClienteCheckbox = document.getElementById('seleccionar_cliente');
    const clienteContainer = document.getElementById('cliente_container');
    const clienteSelect = document.getElementById('cliente_select');

    function toggleClienteField() {
        if (!seleccionarClienteCheckbox || !clienteContainer || !clienteSelect) return;
        
        if (seleccionarClienteCheckbox.checked) {
            clienteContainer.classList.remove('hidden');
            clienteSelect.setAttribute('required', 'required');
        } else {
            clienteContainer.classList.add('hidden');
            clienteSelect.removeAttribute('required');
            clienteSelect.value = ''; // Resetear a cliente anónimo
        }
    }

    // Inicializar y configurar event listener para cliente
    if (seleccionarClienteCheckbox) {
        seleccionarClienteCheckbox.addEventListener('change', toggleClienteField);
        toggleClienteField(); // Estado inicial
    }
    // === FIN CONTROL DE SELECCIÓN DE CLIENTE ===
    
    // === CONTROL DE PAGOS MÚLTIPLES (MODIFICADO) ===
    const pagosContainer = document.getElementById('pagos-container');
    const pagoTemplate = document.getElementById('pago-template');
    const agregarPagoBtn = document.getElementById('agregar-pago');
    const totalPagadoSpan = document.getElementById('total_pagado');
    const restantePagoSpan = document.getElementById('restante_pago');
    const metodoPagoInput = document.getElementById('metodo_pago_input');
    let pagoIndex = 1; // Empezar en 1 porque ya tenemos el pago 0 por defecto

    // Función para actualizar el método de pago principal
    function actualizarMetodoPagoPrincipal() {
        const cantidadPagos = pagosContainer.children.length;
        
        if (cantidadPagos > 1) {
            // Si hay múltiples pagos, establecer como multipago
            metodoPagoInput.value = 'multipago';
        } else {
            // Si hay solo un pago, usar el método seleccionado en ese pago
            const primerPago = pagosContainer.querySelector('.pago-row');
            if (primerPago) {
                const metodoSelect = primerPago.querySelector('.pago-metodo');
                metodoPagoInput.value = metodoSelect.value;
            }
        }
    }

    // Función para agregar fila de pago
    function agregarFilaPago(metodo = '', monto = 0, referencia = '', destinatario = '') {
        const template = pagoTemplate.innerHTML.replace(/INDEX/g, pagoIndex);
        const div = document.createElement('div');
        div.innerHTML = template;
        pagosContainer.appendChild(div);
        
        // Setear valores si se proporcionan
        if (metodo) {
            div.querySelector('.pago-metodo').value = metodo;
        }
        
        if (monto > 0) {
            div.querySelector('.pago-monto').value = monto;
        }
        
        if (referencia) {
            div.querySelector('.pago-referencia').value = referencia;
        }
        
        if (destinatario) {
            div.querySelector('.pago-destinatario').value = destinatario;
        }
        
        // Eventos para la nueva fila
        const quitarBtn = div.querySelector('.quitar-pago');
        quitarBtn.addEventListener('click', function() {
            if (pagosContainer.children.length > 1) {
                div.remove();
                calcularTotalPagado();
                actualizarMetodoPagoPrincipal();
                // Habilitar el botón de quitar del primer pago si quedan múltiples pagos
                const primerQuitarBtn = pagosContainer.querySelector('.quitar-pago');
                if (primerQuitarBtn && pagosContainer.children.length > 1) {
                    primerQuitarBtn.disabled = false;
                }
            }
        });
        
        const metodoSelect = div.querySelector('.pago-metodo');
        metodoSelect.addEventListener('change', function() {
            const destinatarioContainer = div.querySelector('.pago-destinatario-container');
            if (this.value === 'transferencia') {
                destinatarioContainer.classList.remove('hidden');
                destinatarioContainer.querySelector('select').setAttribute('required', 'required');
            } else {
                destinatarioContainer.classList.add('hidden');
                destinatarioContainer.querySelector('select').removeAttribute('required');
            }
            // Actualizar método principal si es el primer pago
            if (pagosContainer.children.length === 1) {
                actualizarMetodoPagoPrincipal();
            }
        });
        
        const montoInput = div.querySelector('.pago-monto');
        montoInput.addEventListener('input', calcularTotalPagado);
        
        // Inicializar visibilidad del destinatario
        const destinatarioContainer = div.querySelector('.pago-destinatario-container');
        if (metodoSelect.value === 'transferencia') {
            destinatarioContainer.classList.remove('hidden');
            destinatarioContainer.querySelector('select').setAttribute('required', 'required');
        }
        
        pagoIndex++;
        calcularTotalPagado();
        actualizarMetodoPagoPrincipal();
        
        // Habilitar el botón de quitar del primer pago ahora que hay múltiples pagos
        const primerQuitarBtn = pagosContainer.querySelector('.quitar-pago');
        if (primerQuitarBtn && pagosContainer.children.length > 1) {
            primerQuitarBtn.disabled = false;
        }
    }

    // Función para calcular total pagado
    function calcularTotalPagado() {
        let totalPagado = 0;
        document.querySelectorAll('.pago-monto').forEach(input => {
            totalPagado += parseFloat(input.value) || 0;
        });
        
        const totalVenta = parseFloat(document.getElementById('total').value) || 0;
        const restante = totalVenta - totalPagado;
        
        totalPagadoSpan.textContent = '$' + totalPagado.toFixed(2);
        
        if (restante > 0) {
            restantePagoSpan.textContent = 'Faltan: $' + restante.toFixed(2);
            restantePagoSpan.className = 'ml-4 text-sm text-red-600';
        } else if (restante < 0) {
            restantePagoSpan.textContent = 'Excedente: $' + Math.abs(restante).toFixed(2);
            restantePagoSpan.className = 'ml-4 text-sm text-orange-600';
        } else {
            restantePagoSpan.textContent = 'Pago completo';
            restantePagoSpan.className = 'ml-4 text-sm text-green-600';
        }
    }

    // Evento para agregar pago
    agregarPagoBtn.addEventListener('click', function() {
        agregarFilaPago();
    });

    // Configurar evento para el primer pago (efectivo por defecto)
    const primerPago = pagosContainer.querySelector('.pago-row');
    if (primerPago) {
        const metodoSelect = primerPago.querySelector('.pago-metodo');
        metodoSelect.addEventListener('change', function() {
            const destinatarioContainer = primerPago.querySelector('.pago-destinatario-container');
            if (this.value === 'transferencia') {
                destinatarioContainer.classList.remove('hidden');
                destinatarioContainer.querySelector('select').setAttribute('required', 'required');
            } else {
                destinatarioContainer.classList.add('hidden');
                destinatarioContainer.querySelector('select').removeAttribute('required');
            }
            // Actualizar método principal
            actualizarMetodoPagoPrincipal();
        });
        
        const montoInput = primerPago.querySelector('.pago-monto');
        montoInput.addEventListener('input', calcularTotalPagado);
    }

    // Cargar pagos iniciales si existen (excluyendo el primero que ya está)
    const pagosIniciales = JSON.parse(ventaData.dataset.pagosIniciales || '[]');
    if (pagosIniciales.length > 0) {
        // El primer pago ya está creado, cargar los adicionales
        for (let i = 1; i < pagosIniciales.length; i++) {
            agregarFilaPago(
                pagosIniciales[i].metodo,
                pagosIniciales[i].monto,
                pagosIniciales[i].referencia,
                pagosIniciales[i].destinatario
            );
        }
        
        // Si hay pagos iniciales, actualizar también el primer pago
        if (pagosIniciales.length > 0) {
            const primerPago = pagosContainer.querySelector('.pago-row');
            if (primerPago && pagosIniciales[0]) {
                primerPago.querySelector('.pago-metodo').value = pagosIniciales[0].metodo;
                primerPago.querySelector('.pago-monto').value = pagosIniciales[0].monto;
                primerPago.querySelector('.pago-referencia').value = pagosIniciales[0].referencia || '';
                
                // Manejar destinatario para transferencias
                if (pagosIniciales[0].metodo === 'transferencia' && pagosIniciales[0].destinatario) {
                    const destinatarioContainer = primerPago.querySelector('.pago-destinatario-container');
                    destinatarioContainer.classList.remove('hidden');
                    primerPago.querySelector('.pago-destinatario').value = pagosIniciales[0].destinatario;
                    primerPago.querySelector('.pago-destinatario').setAttribute('required', 'required');
                }
            }
        }
    }
    
    // Inicializar método de pago principal
    actualizarMetodoPagoPrincipal();
    // === FIN CONTROL DE PAGOS MÚLTIPLES ===
    
    // Obtener datos iniciales del contenedor oculto
    const productosIniciales = JSON.parse(ventaData.dataset.productosIniciales || '[]');

    // Cargar productos iniciales o agregar fila vacía
    if (productosIniciales.length > 0) {
        productosIniciales.forEach(producto => {
            agregarFilaProducto(
                producto.producto_id,
                producto.cantidad,
                producto.precio,
                producto.total_linea,
                producto.stock
            );
        });
    } else {
        agregarFilaProducto();
    }

    // Evento para agregar producto
    agregarProductoBtn.addEventListener('click', function() {
        agregarFilaProducto();
    });
    
    // Función para agregar fila de producto
    function agregarFilaProducto(productoId = null, cantidad = 1, precio = 0, totalLinea = 0, stock = 0) {
        const template = productoTemplate.innerHTML.replace(/INDEX/g, productoIndex);
        const div = document.createElement('div');
        div.innerHTML = template;
        productosContainer.appendChild(div);
        
        // Setear valores si se proporcionan
        if (productoId) {
            const select = div.querySelector('.producto-select');
            select.value = productoId;
            
            // Actualizar mensaje de stock
            const stockDisponible = parseInt(select.options[select.selectedIndex]?.dataset.stock || 0);
            actualizarMensajeStock(div, cantidad, stockDisponible);
        }
        
        if (precio > 0) {
            const precioInput = div.querySelector('.precio-input');
            precioInput.value = precio;
        }
        
        const cantidadInput = div.querySelector('.cantidad-input');
        cantidadInput.value = cantidad;
        
        const totalLineaInput = div.querySelector('.total-linea-input');
        totalLineaInput.value = totalLinea.toFixed(2);
        
        // Eventos para la nueva fila
        const quitarBtn = div.querySelector('.quitar-producto');
        quitarBtn.addEventListener('click', function() {
            div.remove();
            calcularTotales();
        });
        
        const productoSelect = div.querySelector('.producto-select');
        productoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.precio) {
                const precioInput = div.querySelector('.precio-input');
                precioInput.value = selectedOption.dataset.precio;
                
                // Actualizar mensaje de stock
                const stockDisponible = parseInt(selectedOption.dataset.stock || 0);
                const cantidadActual = parseFloat(div.querySelector('.cantidad-input').value) || 0;
                actualizarMensajeStock(div, cantidadActual, stockDisponible);
                
                calcularLinea(div);
            } 
        });
        
        const cantidadInputElement = div.querySelector('.cantidad-input');
        cantidadInputElement.addEventListener('input', function() {
            const selectedOption = div.querySelector('.producto-select').options[div.querySelector('.producto-select').selectedIndex];
            if (selectedOption) {
                const stockDisponible = parseInt(selectedOption.dataset.stock || 0);
                const cantidad = parseFloat(this.value) || 0;
                actualizarMensajeStock(div, cantidad, stockDisponible);
            }
            calcularLinea(div);
        });
        
        const precioInput = div.querySelector('.precio-input');
        precioInput.addEventListener('input', function() {
            calcularLinea(div);
        });
        
        // Calcular la línea si hay valores iniciales
        if (cantidad > 0 && precio > 0) {
            calcularLinea(div);
        }
        
        productoIndex++;
    }
    
    // Actualizar mensaje de stock
    function actualizarMensajeStock(row, cantidad, stockDisponible) {
        const mensajeStock = row.querySelector('.stock-message');
        const cantidadInput = row.querySelector('.cantidad-input');
        
        if (cantidad > stockDisponible) {
            mensajeStock.textContent = `Stock insuficiente. Disponible: ${stockDisponible}`;
            mensajeStock.classList.remove('hidden');
            cantidadInput.setCustomValidity('Cantidad excede el stock disponible');
        } else {
            mensajeStock.classList.add('hidden');
            cantidadInput.setCustomValidity('');
        }
    }
    
    // Calcular total por línea
    function calcularLinea(row) {
        const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
        const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
        const totalLinea = cantidad * precio;
        
        row.querySelector('.total-linea-input').value = totalLinea.toFixed(2);
        calcularTotales();
    }
    
    // Calcular totales generales
    function calcularTotales() {
        let subtotal = 0;
        document.querySelectorAll('.total-linea-input').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        const descuento = 0;
        const impuestos = 0;
        //document.getElementById('subtotal').value = subtotal.toFixed(2); Descomentar cuando se usen descuentos, impuestos "subtotal" entonces
        //document.getElementById('total').value = (subtotal - descuento + impuestos).toFixed(2); Descometar cuando se implmeneten las otras feats
        document.getElementById('total').value = subtotal.toFixed(2);
        
        // Actualizar también el total de pagos
        calcularTotalPagado();
    }
    
    // Calcular totales iniciales
    calcularTotales();
    
    // Validación adicional del formulario
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validar pagos (siempre requerido ahora)
            const totalPagado = parseFloat(totalPagadoSpan.textContent.replace('$', '')) || 0;
            const totalVenta = parseFloat(document.getElementById('total').value) || 0;
            
            if (Math.abs(totalPagado - totalVenta) > 0.01) {
                e.preventDefault();
                alert('La suma de los pagos ($' + totalPagado.toFixed(2) + ') debe ser igual al total de la venta ($' + totalVenta.toFixed(2) + ').');
                return;
            }
            
            // Validar que cada pago tenga método y monto
            let pagosValidos = true;
            let pagoErrors = [];
            
            document.querySelectorAll('.pago-row').forEach((row, index) => {
                const metodo = row.querySelector('.pago-metodo').value;
                const monto = parseFloat(row.querySelector('.pago-monto').value) || 0;
                
                if (!metodo) {
                    pagosValidos = false;
                    pagoErrors.push(`Pago ${index + 1}: Falta seleccionar método`);
                    row.style.border = '2px solid red';
                } else if (monto <= 0) {
                    pagosValidos = false;
                    pagoErrors.push(`Pago ${index + 1}: Monto debe ser mayor a 0`);
                    row.style.border = '2px solid red';
                } else {
                    row.style.border = '';
                }
                
                // Validar destinatario para transferencias
                if (metodo === 'transferencia') {
                    const destinatario = row.querySelector('.pago-destinatario').value;
                    if (!destinatario) {
                        pagosValidos = false;
                        pagoErrors.push(`Pago ${index + 1}: Transferencia requiere destinatario`);
                        row.style.border = '2px solid red';
                    }
                }
            });
            
            if (!pagosValidos) {
                e.preventDefault();
                alert('Errores en pagos:\n' + pagoErrors.join('\n'));
                return;
            }
        });
    }
});
</script>