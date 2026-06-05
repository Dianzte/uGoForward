<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BecaController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/becas/crear', function() {
    return view('becas.crear');
})->name('becas.crear');

Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');

Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');

Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');
