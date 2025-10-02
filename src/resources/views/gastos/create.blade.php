@extends('layouts.app')
@section('title','Crear gasto')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Crear gasto</h1>
  <form method="POST" action="{{ route('gastos.store') }}" class="space-y-4">
    @include('gastos._form', ['gasto' => $gasto])
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-black text-white">Guardar</button>
      <a href="{{ route('gastos.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>
@endsection
