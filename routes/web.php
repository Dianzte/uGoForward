<?php

use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\BecaCalendarioController;
use App\Http\Controllers\BecaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TestSocioemocionalController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PerfilHubController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HOMEPAGE ---
Route::get('/lang/{locale}', [LocaleController::class, 'setLocale'])->name('lang.switch');

Route::get('/', function () {
    return view('homepage');
})->name('index');

Route::get('/homepage', function () {
    return view('homepage');
});


// --- BECAS ---
Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');
Route::get('/becas/crear', [BecaController::class, 'create'])->name('becas.create');
Route::post('/becas/crear', [BecaController::class, 'store'])->name('becas.store');
Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');


// --- RUTAS PARA INVITADOS (NO LOGUEADOS) ---
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [Authcontroller::class, 'showLogin'])->name('login');
    Route::post('/login', [Authcontroller::class, 'login']);

    // Registro
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('registro');
    Route::post('/registro', [AuthController::class, 'register'])->name('registro.store');
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

// --- SELECCIÓN DE ROL (una sola vez por usuario) ---
Route::middleware('auth')->group(function () {
    Route::get('/seleccionar-rol', [RolController::class, 'seleccionar'])->name('rol.seleccionar');
    Route::post('/seleccionar-rol', [RolController::class, 'guardar'])->name('rol.guardar');

    // Destinos según el rol elegido
    Route::get('/test-socioemocional', [TestSocioemocionalController::class, 'index'])->name('test.socioemocional');
    Route::post('/test-socioemocional', [TestSocioemocionalController::class, 'guardar'])->name('test.socioemocional.guardar');

    // Corrección aquí: Se asigna 'padrino.tutorial' como nombre de ruta
    Route::get('/tutorial-padrino', function () {
        return view('padrinotutorial');
    })->name('padrino.tutorial');

    // Logout (fuera del middleware rol.seleccionado para evitar loops)
    Route::post('/logout', [Authcontroller::class, 'logout'])->name('logout');
});

// --- RUTAS QUE REQUIEREN ROL YA SELECCIONADO ---
Route::middleware(['auth', 'rol.seleccionado'])->group(function () {
    // Vistas de Ajustes / Perfil
    Route::get('/ajustes', [Authcontroller::class, 'showSettings'])->name('settings');
    Route::get('/perfil', [Authcontroller::class, 'showSettings'])->name('perfil');

    // Procesar actualización de perfil (soportando PUT/POST y ambos nombres de ruta)
    Route::match(['post', 'put'], '/perfil', [Authcontroller::class, 'updateProfile'])->name('perfil.update');
    Route::match(['post', 'put'], '/ajustes', [Authcontroller::class, 'updateProfile'])->name('settings.update');
});
// --- CHATBOT ---
Route::post('/api/chatbot', [ChatbotController::class, 'chat']);

// ============================================================
// --- STUDENT HUB ---
// ============================================================
Route::prefix('hub')->middleware('auth')->group(function () {

    // --- FEED (Muro Académico) ---
    Route::get('/', [PostController::class, 'index'])->name('hub.feed');
    Route::post('/posts', [PostController::class, 'store'])->name('hub.posts.store');
    Route::post('/posts/{post}/upvote', [PostController::class, 'upvote'])->name('hub.posts.upvote');
    Route::post('/posts/{post}/comentar', [PostController::class, 'comentar'])->name('hub.posts.comentar');

    // --- CHAT (Tiempo Real) ---
    Route::get('/chat', [ChatController::class, 'index'])->name('hub.chat');
    Route::get('/chat/{room:slug}', [ChatController::class, 'show'])->name('hub.chat.room');
    Route::post('/chat/{room}/messages', [ChatController::class, 'store'])->name('hub.chat.store');

    // --- METAS (Goal Tracker) ---
    Route::get('/metas', [GoalController::class, 'index'])->name('hub.goals');
    Route::post('/metas', [GoalController::class, 'store'])->name('hub.goals.store');
    Route::patch('/metas/{goal}', [GoalController::class, 'update'])->name('hub.goals.update');
    Route::post('/metas/{goal}/apoyo', [GoalController::class, 'apoyo'])->name('hub.goals.apoyo');

    // --- PERFIL DE ESTUDIANTE ---
    Route::get('/perfil', [PerfilHubController::class, 'show'])->name('hub.perfil');
    Route::get('/perfil/{user}', [PerfilHubController::class, 'show'])->name('hub.perfil.user');
    Route::get('/perfil/editar', [PerfilHubController::class, 'edit'])->name('hub.perfil.edit');
    Route::patch('/perfil', [PerfilHubController::class, 'update'])->name('hub.perfil.update');
});
