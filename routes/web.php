<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de UGF — agrega esto a tu routes/web.php existente
|--------------------------------------------------------------------------
| Los nombres de ruta ('login', 'Register', 'registro.store', 'home')
| coinciden EXACTAMENTE con los que ya usan tus vistas Blade
| (homepage, login.blade.php, Register.blade.php), así que no hace
| falta tocar los archivos .blade.php para los links/forms.
*/ 

Route::get('/', function () {
    return view('homepage');
})->name('home');

// --- LOGIN ---
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// --- REGISTRO ---
// homepage.blade.php enlaza a route('Register') (con R mayúscula)
Route::get('/registro', [AuthController::class, 'showRegister'])
    ->middleware('guest')
    ->name('Register');

// Register.blade.php envía el form a route('registro.store')
Route::post('/registro', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('registro.store');

// --- LOGOUT ---
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- ÁREA PROTEGIDA (ejemplo) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // crea esta vista o cambia el redirect en el controller
    })->name('dashboard');
});