<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login (resources/views/login.blade.php)
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Muestra el formulario de registro (resources/views/Register.blade.php)
     */
    public function showRegister()
    {
        return view('Register');
    }

    /**
     * Procesa el login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', '¡Bienvenido de nuevo!');
    }

    /**
     * Procesa el registro.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'usuario' => ['required', 'string', 'max:255', 'unique:users,usuario'],
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'contrasena' => ['required', 'string', 'min:8'],
            'fechaNac' => ['required', 'date', 'before:today'],
            'departamento' => ['required', 'string', 'max:255'],
            'nie' => ['nullable', 'string', 'max:20'],
            'dui' => ['nullable', 'string', 'max:20'],
        ]);

        // La edad se recalcula en el servidor: nunca confiar solo en el JS del formulario.
        $edad = now()->diffInYears($validated['fechaNac']);

        if ($edad < 16) {
            throw ValidationException::withMessages([
                'fechaNac' => 'Debes tener al menos 16 años para registrarte.',
            ]);
        }

        if ($edad < 18) {
            $request->validate(['nie' => ['required', 'string', 'max:20']]);
        } else {
            $request->validate(['dui' => ['required', 'string', 'max:20']]);
        }

        $user = User::create([
            'usuario' => $validated['usuario'],
            'nombre' => $validated['nombre'],
            'email' => $validated['correo'],
            'password' => Hash::make($validated['contrasena']),
            'fecha_nac' => $validated['fechaNac'],
            'departamento' => $validated['departamento'],
            'nie' => $edad < 18 ? $request->input('nie') : null,
            'dui' => $edad >= 18 ? $request->input('dui') : null,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('status', '¡Cuenta creada con éxito! Bienvenido a UGF.');
    }

    /**
     * Cierra sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}