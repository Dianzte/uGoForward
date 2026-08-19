<?php

use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\BecaCalendarioController;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ForoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('index');

Route::get('/homepage', function () {
    return view('homepage');
});


// --- RUTAS PARA INVITADOS (NO LOGUEADOS) ---
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [Authcontroller::class, 'showLogin'])->name('login');
    Route::post('/login', [Authcontroller::class, 'login']);

    // Registro
    Route::get('/registro', [Authcontroller::class, 'showRegister'])->name('registro');
    Route::post('/registro', [Authcontroller::class, 'register'])->name('registro.store');
});

Route::get('/calendario', function () {
    return view('calendario');
});

Route::get('/becas-calendario', function () {
    return view('calendario');
})->name('becas.calendario');

Route::middleware('auth')->group(function(){

    Route::post('/foro/crear', [ForoController::class, 'store'])->name('foro.store');
    Route::get('/foro/crear', [ForoController::class, 'create'])->name('foro.crear');
    });

Route::get('/foro', [ForoController::class, 'index'])->name('foro.index');
Route::get('/foro/{foro:slug}', [ForoController::class, 'show'])->name('foro.show');
Route::post('/foro/{ejemplo:slug}', [ComentarioController::class, 'store'])->name('comentario.store');



Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');
Route::get('/becas/index', [BecaController::class, 'filtrar'])->name('becas.filtrar');
Route::middleware('auth')->group(function(){
    Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');
    Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');
    });

Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');


Route::get('/api/becas-calendario/eventos', [BecaCalendarioController::class, 'obtenerEventos']);

Route::post('/api/calendario/tareas', [BecaCalendarioController::class, 'guardarTarea']);

// Rutas para Modificar y Eliminar Tareas de la Agenda
Route::put('/api/calendario/tareas/{id}', [BecaCalendarioController::class, 'actualizarTarea']);
Route::delete('/api/calendario/tareas/{id}', [BecaCalendarioController::class, 'eliminarTarea']);

Route::middleware('auth')->group(function () {
    // Vistas de Ajustes / Perfil
    Route::get('/ajustes', [Authcontroller::class, 'showSettings'])->name('settings');
    Route::get('/perfil', [Authcontroller::class, 'showSettings'])->name('perfil');

    // Procesar actualización de perfil (soportando PUT/POST y ambos nombres de ruta)
    Route::match(['post', 'put'], '/perfil', [Authcontroller::class, 'updateProfile'])->name('perfil.update');
    Route::match(['post', 'put'], '/ajustes', [Authcontroller::class, 'updateProfile'])->name('settings.update');

    // Logout
    Route::post('/logout', [Authcontroller::class, 'logout'])->name('logout');
});
