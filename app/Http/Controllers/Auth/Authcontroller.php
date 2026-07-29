<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Muestra el formulario de registro.
     */
    public function showRegister()
    {
        return view('Register');
    }

    /**
     * Muestra la vista de ajustes/perfil.
     */
    public function showSettings()
    {
        return view('settings');
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['correo' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('status', '¡Bienvenido de nuevo!');
        }

        throw ValidationException::withMessages([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Procesa el registro de usuario.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'usuario' => ['required', 'string', 'max:255', 'unique:users,usuario'],
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:users,correo'],
            'contrasena' => ['required', 'string', 'min:4'],
            'fechaNac' => ['required', 'date', 'before:today'],
            'departamento' => ['required', 'string', 'max:255'],
            'nie' => ['nullable', 'string', 'max:20'],
            'dui' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'usuario' => $validated['usuario'],
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'contrasena' => $validated['contrasena'],
            'fechaNac' => $validated['fechaNac'],
            'departamento' => $validated['departamento'],
            'nie' => $request->input('nie'),
            'dui' => $request->input('dui'),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('index')->with('status', '¡Cuenta creada con éxito!');
    }

    /**
     * Procesa la actualización del perfil/ajustes.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'departamento' => ['required', 'string', 'max:255'],
            'contrasena' => ['nullable', 'string', 'min:8'],
        ]);

        $user->nombre = $validated['nombre'];
        $user->departamento = $validated['departamento'];

        if ($request->filled('contrasena')) {
            $user->contrasena = Hash::make($validated['contrasena']);
        }

        $user->save();

        return back()->with('status', 'Perfil actualizado correctamente.');
    }

    /**
     * Cierra la sesión activa.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}