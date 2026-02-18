<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Controlador encargado de la autenticación de usuarios.
 * Gestiona el registro, inicio de sesión (login) y cierre de sesión (logout).
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function showRegister()
    {
        // Retorna la vista ubicada en resources/views/auth/register.blade.php
        return view('auth.register');
    }

    /**
     * Procesa los datos del formulario de registro y crea un nuevo usuario.
     */
    public function register(Request $request)
    {
        // 1. Validación de los datos que llegan del formulario.
        // Si alguna regla falla, Laravel detiene la ejecución y redirige automáticamente de vuelta con los errores.
        $validate = $request->validate([
            'dni' => 'required|unique:users,dni|max:9',
            'name' => 'required|string|max:50',
            'lastname' => 'required|string|max:100',
            'username' => 'required|unique:users,username|max:15',
            'email' => 'required|email|unique:users,email',
            'addrs' => 'required|string|max:255',
            // La regla 'confirmed' exige que en el formulario exista un campo llamado 'password_confirmation' que coincida exactamente.
            'password' => 'required|confirmed|min:4'
        ]);

        // 2. Creación del usuario en la base de datos con los datos validados.
        // La contraseña se encripta automáticamente si hemos configurado los "casts" en el modelo User (hashed).
        $user = User::create($validate);

        // 3. Autenticar (loguear) al usuario automáticamente nada más registrarse.
        Auth::login($user);

        // 4. Redirigir a la página principal (usando el nombre de la ruta definida en web.php).
        return redirect()->route('home');
    }

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin()
    {
        // Retorna la vista ubicada en resources/views/auth/login.blade.php
        return view('auth.login');
    }

    /**
     * Procesa las credenciales enviadas para intentar iniciar sesión.
     */
    public function login(Request $request)
    {
        // 1. Validar que nos envían el email y la contraseña con el formato correcto.
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Intentar autenticar al usuario con las credenciales dadas.
        // Auth::attempt() verifica automáticamente si el email existe y si el hash de la contraseña coincide.
        if (Auth::attempt($credentials)) {
            // Si el login es correcto, regeneramos el ID de la sesión.
            // Esto es una medida de seguridad vital para evitar ataques de "Session Fixation" (Secuestro de sesión).
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        // 3. Si las credenciales son incorrectas, llegamos a este punto y lanzamos una excepción.
        // Esto redirige al usuario de vuelta al login y manda este mensaje de error personalizado.
        throw ValidationException::withMessages([
            'credentials' => 'Error, credenciales no válidas'
        ]);
    }

    /**
     * Cierra la sesión del usuario actual por motivos de seguridad.
     */
    public function logout(Request $request)
    {
        // 1. Cierra la sesión en el sistema de autenticación de Laravel.
        Auth::logout();

        // 2. Invalida la sesión actual borrando todos los datos guardados en ella.
        $request->session()->invalidate();

        // 3. Regenera el token CSRF para proteger contra ataques en los siguientes formularios que vea el usuario.
        $request->session()->regenerateToken();

        // 4. Redirige a la página principal tras salir.
        return redirect()->route('home');
    }
}
