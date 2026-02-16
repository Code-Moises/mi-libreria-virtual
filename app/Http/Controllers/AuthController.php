<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Muestra la vista de registro
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validamos todos los campos
        $validate = $request->validate([
            'dni' => 'required|unique:users,dni|max:20',
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'username' => 'required|unique:users,username|max:100',
            'email' => 'required|email|unique:users,email',
            'addrs' => 'required|string|max:255',
            'password' => 'required|confirmed|min:4' // confirmed busca un campo password_confirmation
        ]);

        // 2. Creamos el usuario (la contraseña se hashea sola en el Modelo)
        $user = User::create($validate);

        // 3. Login automático
        Auth::login($user);

        // 4. Redirección
        return redirect()->route('home');
    }

    // Muestra la vista de login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Intentamos loguear (remember: false por defecto)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        // Si falla, lanzamos excepción
        throw ValidationException::withMessages([
            'credentials' => 'Error, credenciales no válidas'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
