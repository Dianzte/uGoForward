<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Muestra la vista de registro (Register.blade.php)
     */
    public function showRegister()
    {
        return view('Register');
    }

    /**
     * Procesa el formulario de registro
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'usuario'      => 'required|string|max:255|unique:users,usuario',
            'nombre'       => 'required|string|max:255',
            'correo'       => 'required|string|email|max:255|unique:users,correo',
            'contrasena'   => ['required', 'confirmed', Rules\Password::defaults()],
            'fechaNac'     => 'required|date|before:-16 years', // exige minimo 16 anios, igual que el JS del front
            'departamento' => 'required|string|max:255',
            'nie'          => 'nullable|string|max:255|required_without:dui',
            'dui'          => 'nullable|string|max:255|required_without:nie',
        ]);

        $user = User::create([
            'usuario'      => $validated['usuario'],
            'nombre'       => $validated['nombre'],
            'correo'       => $validated['correo'],
            'contrasena'   => Hash::make($validated['contrasena']),
            'fechaNac'     => $validated['fechaNac'],
            'departamento' => $validated['departamento'],
            'nie'          => $validated['nie'] ?? null,
            'dui'          => $validated['dui'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('status', '¡Cuenta creada exitosamente!');
    }

    /**
     * Muestra la vista de login (login.blade.php)
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Procesa el formulario de login
     * Nota: el input del form se llama "email" pero la columna real es "correo",
     * y el input "password" se compara contra "contrasena" via getAuthPassword().
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['correo' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
