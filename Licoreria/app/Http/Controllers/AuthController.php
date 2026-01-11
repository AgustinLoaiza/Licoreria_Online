<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => 'Datos de inicio de sesión incorrectos.',
        ]);
    }

    public function showregister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:cliente,repartidor',
        ]);

        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'rol' => $validated['rol'],
        ]);

        Auth::login($usuario);
        return $this->redirectByRole($usuario);
    }

    private function redirectByRole($user)
    {
        switch ($user->rol)
        {
            case 'administrador':
                return redirect()->route('dashboard.administrador');
            case 'repartidor':
                return redirect()->route('dashboard.repartidor');
            case 'cliente':
            default:
                return redirect()->route('dashboard.cliente');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
