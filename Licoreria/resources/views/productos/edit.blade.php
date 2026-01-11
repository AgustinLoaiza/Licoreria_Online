@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto: ' . $producto->nombre)
@section('page-description', 'Modifica la información del producto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Información Básica -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Información Básica</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Producto *
                            </label>
                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre', $producto->nombre) }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('nombre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Producto *
                            </label>
                            <select id="tipo"
                                    name="tipo"
                                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">Selecciona un tipo</option>
                                <option value="bebida" {{ old('tipo', $producto->tipo) == 'bebida' ? 'selected' : '' }}>Bebida</option>
                                <option value="comida" {{ old('tipo', $producto->tipo) == 'comida' ? 'selected' : '' }}>Comida</option>
                                <option value="combo" {{ old('tipo', $producto->tipo) == 'combo' ? 'selected' : '' }}>Combo</option>
                                <option value="accesorio" {{ old('tipo', $producto->tipo) == 'accesorio' ? 'selected' : '' }}>Accesorio</option>
                            </select>
                            @error('tipo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Precio y Stock -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Precio y Stock</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Precio -->
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700 mb-2">
                                Precio (S/) *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">S/</span>
                                </div>
                                <input type="number"
                                       id="precio"
                                       name="precio"
                                       value="{{ old('precio', $producto->precio) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       required>
                            </div>
                            @error('precio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-2">
                                Cantidad en Stock *
                            </label>
                            <input type="number"
                                   id="cantidad"
                                   name="cantidad"
                                   value="{{ old('cantidad', $producto->cantidad) }}"
                                   min="0"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('cantidad')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Imagen del Producto</h3>

                    <div>
                        <label for="imagen" class="block text-sm font-medium text-gray-700 mb-2">
                            Imagen (opcional)
                        </label>

                        <!-- Imagen actual -->
                        @if($producto->imagen)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                            <div class="flex items-center space-x-4">
                                <div class="h-32 w-32 bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                                         alt="{{ $producto->nombre }}"
                                         class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <button type="button"
                                            onclick="eliminarImagen()"
                                            class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm hover:bg-red-200">
                                        <i class="fas fa-trash mr-1"></i> Eliminar imagen
                                    </button>
                                    <input type="hidden" id="eliminar_imagen" name="eliminar_imagen" value="0">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Subir nueva imagen -->
                        <div class="mt-1 flex items-center">
                            <div class="flex-shrink-0 h-32 w-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden">
                                <img id="imagePreview"
                                     src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : 'https://via.placeholder.com/150?text=Sin+imagen' }}"
                                     alt="Vista previa"
                                     class="h-full w-full object-cover">
                            </div>
                            <div class="ml-4">
                                <input type="file"
                                       id="imagen"
                                       name="imagen"
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100"
                                       onchange="previewImage(this)">
                                <p class="text-xs text-gray-500 mt-2">
                                    Deja vacío para mantener la imagen actual. Formatos: JPG, PNG, GIF, WEBP. Max: 2MB
                                </p>
                            </div>
                        </div>
                        @error('imagen')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Descripción</h3>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción del Producto
                        </label>
                        <textarea id="descripcion"
                                  name="descripcion"
                                  rows="4"
                                  class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Describe las características del producto (opcional)
                        </p>
                        @error('descripcion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Estado</h3>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="activo"
                               name="activo"
                               value="1"
                               {{ old('activo', $producto->activo) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="activo" class="ml-2 block text-sm text-gray-700">
                            Producto activo (visible para clientes)
                        </label>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('productos.show', $producto) }}"
                       class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-save mr-2"></i> Actualizar Producto
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function eliminarImagen() {
        document.getElementById('eliminar_imagen').value = '1';
        document.getElementById('imagePreview').src = 'https://via.placeholder.com/150?text=Sin+imagen';

        // Mostrar mensaje de confirmación
        showNotification('Imagen marcada para eliminación', 'info');
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${
            type === 'info' ? 'bg-blue-100 text-blue-800 border-blue-300' :
            type === 'success' ? 'bg-green-100 text-green-800 border-green-300' :
            'bg-red-100 text-red-800 border-red-300'
        } border-l-4`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'info' ? 'info-circle' : type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Validación de precio
    document.getElementById('precio').addEventListener('input', function(e) {
        if (this.value < 0) {
            this.value = 0;
        }
    });

    // Validación de cantidad
    document.getElementById('cantidad').addEventListener('input', function(e) {
        if (this.value < 0) {
            this.value = 0;
        }
    });
</script>
@endpush
@endsection
