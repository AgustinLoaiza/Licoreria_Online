@extends('layouts.admin')

@section('title', 'Nuevo Usuario')
@section('page-title', 'Crear Nuevo Usuario')
@section('page-description', 'Agrega un nuevo usuario al sistema')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">
        <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="nombre" name="nombre" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" name="email" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>

            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" id="telefono" name="telefono"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>

            <div>
                <label for="rol" class="block text-sm font-medium text-gray-700">Rol</label>
                <select id="rol" name="rol" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="administrador">Administrador</option>
                    <option value="cliente">Cliente</option>
                    <option value="repartidor">Repartidor</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
