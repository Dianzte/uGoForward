<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HOMEPAGE ---
Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::get('/homepage', function () {
    return redirect()->route('home');
});


// --- BECAS ---
Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');
Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');
Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');
Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');


// --- RUTAS PARA INVITADOS (NO LOGUEADOS) ---
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registro
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('Register');
    Route::post('/registro', [AuthController::class, 'register'])->name('registro.store');
});


// --- RUTAS AUTENTICADAS (LOGUEADOS) ---
Route::middleware('auth')->group(function () {
    // Vistas de Ajustes / Perfil
    Route::get('/ajustes', [AuthController::class, 'showSettings'])->name('settings');
    Route::get('/perfil', [AuthController::class, 'showSettings'])->name('perfil');

    // Procesar actualización de perfil (soportando PUT/POST y ambos nombres de ruta)
    Route::match(['post', 'put'], '/perfil', [AuthController::class, 'updateProfile'])->name('perfil.update');
    Route::match(['post', 'put'], '/ajustes', [AuthController::class, 'updateProfile'])->name('settings.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});