
@extends('layouts.app')
@section('title','Crear Empleado')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Crear Empleado</h1>

  <form method="POST" action="{{ route('empleados.store') }}" class="space-y-4">
      @csrf
      @include('empleados._form', ['empleado' => $empleado])

      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 rounded-lg bg-black text-white">
          Guardar
        </button>
        <a href="{{ route('empleados.index') }}" class="px-4 py-2 rounded-lg border">
          Cancelar
        </a>
      </div>
  </form>
</div>
@endsection
