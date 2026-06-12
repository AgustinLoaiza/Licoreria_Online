@extends('layouts.app')

@section('title', 'Registro - R&E Bebidas')

@section('content')
<section class="min-h-screen bg-gradient-primary px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] w-full max-w-6xl items-center gap-8 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="hidden text-white lg:block">
            <img src="{{ asset('images/logo-licoreria.jpeg') }}"
                 alt="R&E Distribuidora de Bebidas"
                 class="mb-8 h-36 w-36 rounded-2xl border border-white/10 object-cover shadow-2xl">
            <h1 class="max-w-xl text-4xl font-black leading-tight">
                Crea tu cuenta y compra tus bebidas en minutos.
            </h1>
            <p class="mt-5 max-w-lg leading-7 text-gray-200">
                Accede al catalogo, arma tu carrito y coordina entregas con pago por QR, efectivo o tarjeta.
            </p>
        </div>

        <div class="mx-auto w-full max-w-xl">
            <div class="rounded-2xl border border-white/10 bg-white p-6 shadow-2xl sm:p-8">
                <div class="text-center">
                    <img src="{{ asset('images/logo-licoreria.jpeg') }}"
                         alt="R&E Distribuidora de Bebidas"
                         class="mx-auto h-24 w-24 rounded-2xl object-cover shadow-lg">
                    <h2 class="mt-5 text-2xl font-black text-gray-950 sm:text-3xl">Crear cuenta</h2>
                    <p class="mt-2 text-sm text-gray-600">Registrate para comenzar a comprar.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-auto-dismiss mt-6 rounded-lg border-l-4 border-red-600 bg-red-50 p-4">
                        <div class="flex gap-3">
                            <i class="fas fa-circle-exclamation mt-0.5 text-red-500"></i>
                            <div class="text-sm text-red-800">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form class="mt-7 space-y-5" action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="nombre" class="mb-2 block text-sm font-semibold text-gray-800">Nombre completo</label>
                            <input id="nombre" name="nombre" type="text" required value="{{ old('nombre') }}"
                                   class="input-focus w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none"
                                   placeholder="Juan Perez">
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-gray-800">Correo electronico</label>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                   class="input-focus w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none"
                                   placeholder="tu@email.com">
                        </div>

                        <div>
                            <label for="telefono" class="mb-2 block text-sm font-semibold text-gray-800">Telefono</label>
                            <input id="telefono" name="telefono" type="tel" value="{{ old('telefono') }}"
                                   class="input-focus w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none"
                                   placeholder="+51 999 999 999">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="rol" class="mb-2 block text-sm font-semibold text-gray-800">Tipo de cuenta</label>
                            <select id="rol" name="rol" required
                                    class="input-focus w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none">
                                <option value="">Selecciona un rol</option>
                                <option value="cliente" {{ old('rol') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                <option value="repartidor" {{ old('rol') == 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                            </select>
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-gray-800">Contrasena</label>
                            <input id="password" name="password" type="password" required
                                   class="input-focus w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none"
                                   placeholder="Minimo 8 caracteres">
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-800">Confirmar contrasena</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="input-focus w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none"
                                   placeholder="Repite tu contrasena">
                        </div>
                    </div>

                    <label class="flex items-start gap-3 text-sm text-gray-700">
                        <input id="terms" name="terms" type="checkbox" required class="mt-1 h-4 w-4 rounded border-gray-300 text-red-700 focus:ring-red-600">
                        <span>Acepto los terminos y condiciones del servicio.</span>
                    </label>

                    <button type="submit"
                            class="btn-primary flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-black text-white shadow-lg shadow-red-900/20 transition hover:-translate-y-0.5">
                        <i class="fas fa-user-plus mr-2"></i>Crear cuenta
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="font-bold text-red-700 hover:text-red-600">Inicia sesion aqui</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
