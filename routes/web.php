<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\BecaCalendarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HOMEPAGE ---
Route::get('/', function () {
    return view('homepage');
})->name('index');

Route::get('/homepage' , function () {
    return view('homepage');
});


Route::get('/registro', [Authcontroller::class, 'showRegister'])->name('registro');


Route::post('/registrar', [Authcontroller::class, 'register'])->name('registro.store');

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

Route::get('/calendario', function () {
    return view('calendario');
});

Route::get('/becas-calendario', function () {
    return view('calendario'); 
})->name('becas.calendario');



Route::get('/foro',  [ForoController::class, 'index'])->name('foro.index');
Route::get('/foro/crear',  [ForoController::class, 'create']);
Route::post('/foro/crear',  [ForoController::class, 'store'])->name('foro.store');
Route::get('/foro/{foro:slug}', [ForoController::class, 'show'])->name('foro.show');
Route::post('/foro/{ejemplo:slug}', [ComentarioController::class, 'store'])->name('comentario.store');
Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');

Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');

Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');

Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');

Route::get('/api/becas-calendario/eventos', [BecaCalendarioController::class, 'obtenerEventos']);

Route::post('/api/calendario/tareas', [BecaCalendarioController::class, 'guardarTarea']);

// Rutas para Modificar y Eliminar Tareas de la Agenda
Route::put('/api/calendario/tareas/{id}', [BecaCalendarioController::class, 'actualizarTarea']);
Route::delete('/api/calendario/tareas/{id}', [BecaCalendarioController::class, 'eliminarTarea']);



