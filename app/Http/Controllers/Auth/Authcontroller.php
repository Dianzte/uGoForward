<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Imagen;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        return view('Login');
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

            return redirect()->intended(route('index'))->with('status', '¡Bienvenido de nuevo!');
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
            'contrasena' => Hash::make($validated['contrasena']),
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
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'departamento' => ['required', 'string', 'max:255'],
            'contrasena' => ['nullable', 'string', 'min:8'],
             'nie' => ['nullable', 'string', 'max:20'],
            'dui' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarAntiguo = $user->avatarImg;
        $bannerAntiguo = $user->bannerImg;

        if ($request->hasFile('avatar')) {
            $rutaArchivo = $request->file('avatar')->store('imagenes', 'public');

            $imagenCreada = Imagen::create([
                'ruta' => $rutaArchivo,
            ]);

            $avatar = $imagenCreada->id;
            $user->avatar = $avatar;

            if ($avatarAntiguo) {
                Storage::disk('public')->delete('imagenes/'.$avatarAntiguo->ruta);
                $avatarAntiguo->delete();
            }
        } else {

            $user->avatar = $user->avatar;
        }

        if ($request->hasFile('banner')) {
            $rutaArchivo = $request->file('banner')->store('imagenes', 'public');

            $imagenCreada = Imagen::create([
                'ruta' => $rutaArchivo,
            ]);

            $banner = $imagenCreada->id;
            $user->banner = $banner;

            if ($bannerAntiguo) {
                Storage::disk('public')->delete('imagenes/'.$bannerAntiguo->ruta);
                $bannerAntiguo->delete();
            }
        } else {
            $user->banner = $user->banner;
        }

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

        return redirect()->route('index');
    }
}
