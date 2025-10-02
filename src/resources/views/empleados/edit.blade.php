
@extends('layouts.app')
@section('title','Editar Empleado')
@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Editar Empleado: {{ $empleado->nombre }}</h1>
  <form method="POST" action="{{ route('empleados.update', $empleado) }}" class="space-y-4">
    @method('PUT')
    @include('empleados._form', ['empleado' => $empleado])
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg bg-black text-white">Actualizar</button>
      <a href="{{ route('empleados.index') }}" class="px-4 py-2 rounded-lg border">Cancelar</a>
    </div>
  </form>
</div>
@endsection
