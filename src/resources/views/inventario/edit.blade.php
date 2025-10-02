@extends('layouts.app')
@section('title','Editar inventario')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Editar inventario: {{ $existencia->producto->nombre }}</h1>
  <form method="POST" action="{{ route('inventario.update', $existencia) }}" class="space-y-4">
    @method('PUT')
    @include('inventario._form', ['existencia' => $existencia])
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-black text-white">Actualizar Stock</button>
      <a href="{{ route('inventario.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>
@endsection