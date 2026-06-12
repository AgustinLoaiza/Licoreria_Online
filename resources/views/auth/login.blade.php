@extends('layouts.app')

@section('title', 'Iniciar sesion - R&E Bebidas')

@section('content')
<section class="min-h-screen bg-gradient-primary px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto grid min-h-[calc(100vh-4rem)] w-full max-w-6xl items-center gap-8 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="hidden text-white lg:block">
            <div class="max-w-xl">
                <img src="{{ asset('images/logo-licoreria.jpeg') }}"
                     alt="R&E Distribuidora de Bebidas"
                     class="mb-8 h-36 w-36 rounded-2xl border border-white/10 object-cover shadow-2xl">

                <p class="mb-3 inline-flex items-center rounded-full border border-amber-300/40 bg-white/10 px-4 py-2 text-sm font-semibold text-amber-100 backdrop-blur">
                    <i class="fas fa-bolt mr-2 text-amber-300"></i>
                    Delivery de bebidas y complementos
                </p>

                <h1 class="text-4xl font-black leading-tight tracking-normal sm:text-5xl">
                    R&E Distribuidora de Bebidas
                </h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-gray-200">
                    Gestiona pedidos, productos y entregas desde una experiencia segura, rapida y pensada para vender mejor.
                </p>

                <div class="mt-8 grid max-w-lg grid-cols-3 gap-3">
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <i class="fas fa-truck-fast mb-3 text-2xl text-amber-300"></i>
                        <p class="text-sm font-semibold">Entrega rapida</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <i class="fas fa-qrcode mb-3 text-2xl text-amber-300"></i>
                        <p class="text-sm font-semibold">Pago QR</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <i class="fas fa-boxes-stacked mb-3 text-2xl text-amber-300"></i>
                        <p class="text-sm font-semibold">Stock activo</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto w-full max-w-md">
            <div class="rounded-2xl border border-white/10 bg-white p-6 shadow-2xl sm:p-8">
                <div class="text-center">
                    <img src="{{ asset('images/logo-licoreria.jpeg') }}"
                         alt="R&E Distribuidora de Bebidas"
                         class="mx-auto h-28 w-28 rounded-2xl object-cover shadow-lg">
                    <h2 class="mt-5 text-2xl font-black text-gray-950 sm:text-3xl">
                        Bienvenido de nuevo
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Inicia sesion para continuar con la licoreria online.
                    </p>
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

                @if (session('success'))
                    <div class="alert-auto-dismiss mt-6 rounded-lg border-l-4 border-emerald-600 bg-emerald-50 p-4 text-sm text-emerald-800">
                        <i class="fas fa-circle-check mr-2 text-emerald-600"></i>{{ session('success') }}
                    </div>
                @endif

                <form class="mt-7 space-y-5" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-800">
                            Correo electronico
                        </label>
                        <div class="relative">
                            <i class="fas fa-at absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   autocomplete="email"
                                   required
                                   value="{{ old('email') }}"
                                   class="input-focus block w-full rounded-lg border border-gray-300 bg-white py-3 pl-10 pr-3 text-sm text-gray-950 outline-none transition"
                                   placeholder="tu@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-gray-800">
                            Contrasena
                        </label>
                        <div class="relative">
                            <i class="fas fa-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="current-password"
                                   required
                                   class="input-focus block w-full rounded-lg border border-gray-300 bg-white py-3 pl-10 pr-12 text-sm text-gray-950 outline-none transition"
                                   placeholder="Ingresa tu contrasena">
                            <button type="button"
                                    id="toggle-password"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gray-700"
                                    aria-label="Mostrar contrasena">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="btn-primary flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-bold text-white shadow-lg shadow-red-900/20 transition hover:-translate-y-0.5">
                        <i class="fas fa-right-to-bracket mr-2"></i>
                        Iniciar sesion
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        No tienes una cuenta?
                        <a href="{{ route('register') }}" class="font-bold text-red-700 hover:text-red-600">
                            Registrate aqui
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('toggle-password')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = this.querySelector('i');
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isPassword);
        icon.classList.toggle('fa-eye-slash', isPassword);
    });
</script>
@endpush
