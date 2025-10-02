@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Iniciar Sesión</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" 
                       class="w-full p-3 border rounded focus:ring-2 focus:ring-yellow-500" 
                       required autofocus>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Contraseña</label>
                <input type="password" name="password" 
                       class="w-full p-3 border rounded focus:ring-2 focus:ring-yellow-500" 
                       required>
            </div>

            <button type="submit" 
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded transition">
                Iniciar Sesión
            </button>
        </form>
    </div>
</div>
@endsection
