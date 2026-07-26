<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\ComentarioController;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/homepage' , function () {
    return view('homepage');
});

Route::get('/becas', function () {
    return view('becas.index'); 
});


Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');

Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');

Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');

Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');


Route::get('/foro',  [ForoController::class, 'index'])->name('foro.index');
Route::get('/foro/crear',  [ForoController::class, 'create']);
Route::post('/foro/crear',  [ForoController::class, 'store'])->name('foro.store');
Route::get('/foro/{foro:slug}', [ForoController::class, 'show'])->name('foro.show');
Route::post('/foro/{ejemplo:slug}', [ComentarioController::class, 'store'])->name('comentario.store');
