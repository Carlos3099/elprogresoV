@extends('layouts.app')
@section('title','Editar producto')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Editar producto: {{ $producto->nombre }}</h1>
  <form method="POST" action="{{ route('productos.update', $producto) }}" class="space-y-4">
    @method('PUT')
    @include('productos._form', ['producto' => $producto])
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-black text-white">Actualizar</button>
      <a href="{{ route('productos.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>
@endsection