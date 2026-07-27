<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\BecaCalendarioController;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/homepage' , function () {
    return view('homepage');
});

Route::get('/becas', function () {
    return view('becas.index'); 
});

Route::get('/calendario', function () {
    return view('calendario');
});

Route::get('/becas-calendario', function () {
    return view('calendario'); 
})->name('becas.calendario');



Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');

Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');

Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');

Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');

Route::get('/api/becas-calendario/eventos', [BecaCalendarioController::class, 'obtenerEventos']);

Route::post('/api/calendario/tareas', [BecaCalendarioController::class, 'guardarTarea']);

// Rutas para Modificar y Eliminar Tareas de la Agenda
Route::put('/api/calendario/tareas/{id}', [BecaCalendarioController::class, 'actualizarTarea']);
Route::delete('/api/calendario/tareas/{id}', [BecaCalendarioController::class, 'eliminarTarea']);



