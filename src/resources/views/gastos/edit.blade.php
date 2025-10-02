@extends('layouts.app')
@section('title','Editar gasto')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Editar gasto #{{ $gasto->id }}</h1>
  <form method="POST" action="{{ route('gastos.update', $gasto) }}" class="space-y-4">
    @method('PUT')
    @include('gastos._form', ['gasto' => $gasto])
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-black text-white">Actualizar</button>
      <a href="{{ route('gastos.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>
@endsection
