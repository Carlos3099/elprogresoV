@extends('layouts.app')
@section('title','Editar venta')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Editar venta #{{ $venta->id }}</h1>
  <form method="POST" action="{{ route('ventas.update', $venta) }}" class="space-y-4">
    @method('PUT')
    @include('ventas._form', ['venta' => $venta])
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-black text-white">Actualizar</button>
      <a href="{{ route('ventas.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>
@endsection
