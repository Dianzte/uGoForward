<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\ForoController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');

Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');

Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');

Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');


Route::get('/foro',  [ForoController::class, 'index']);
