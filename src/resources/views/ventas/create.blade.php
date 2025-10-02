@extends('layouts.app')
@section('title','Crear venta')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Crear venta</h1>
  
  <!-- Mostrar mensajes de error -->
  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
  <!-- Mostrar mensajes de sesión -->
  @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ session('error') }}
    </div>
  @endif
  
  <form method="POST" action="{{ route('ventas.store') }}" class="space-y-4" id="venta-form">
    @include('ventas._form', ['venta' => $venta])
    <div class="flex gap-2">
      <button type="submit" class="px-4 py-2 rounded-lg bg-black text-white">Guardar</button>
      <a href="{{ route('ventas.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>

<!-- Script para debugging -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('venta-form');
    
    form.addEventListener('submit', function(e) {
      // Validar que haya al menos un producto
      const productos = document.querySelectorAll('.producto-row');
      if (productos.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto a la venta.');
        return;
      }
      
      // Validar que cada producto tenga cantidad y precio
      let valid = true;
      document.querySelectorAll('.producto-row').forEach(row => {
        const cantidad = row.querySelector('.cantidad-input').value;
        const precio = row.querySelector('.precio-input').value;
        const producto = row.querySelector('.producto-select').value;
        
        if (!producto || !cantidad || !precio) {
          valid = false;
          row.style.border = '1px solid red';
        } else {
          row.style.border = '';
        }
      });
      
      if (!valid) {
        e.preventDefault();
        alert('Todos los productos deben tener producto seleccionado, cantidad y precio válidos.');
      }
      
      console.log('Formulario enviado');
    });
  });
</script>
@endsection