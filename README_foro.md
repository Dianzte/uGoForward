# Integración del Foro Estudiantil

Este directorio contiene los archivos estructurados para la pantalla de **Foro Estudiantil** (basada en el diseño Figma del proyecto **CreaJ MP**).

## Archivos Instalados
*   **Vistas Blade:**
    *   Listado de Foros: [resources/views/foro/index.blade.php](file:///C:/laragon/www/ugf/resources/views/foro/index.blade.php)
    *   Formulario de Creación: [resources/views/foro/create.blade.php](file:///C:/laragon/www/ugf/resources/views/foro/create.blade.php)
*   **Estilos CSS (Vite):**
    *   Estilos del Listado: [resources/css/foro/index.css](file:///C:/laragon/www/ugf/resources/css/foro/index.css)
    *   Estilos del Formulario: [resources/css/foro/create.css](file:///C:/laragon/www/ugf/resources/css/foro/create.css)

---

## Cómo Integrar en tu Proyecto Laravel

### 1. Crear el Modelo y la Migración
Si aún no tienes una tabla de foros/hilos, ejecuta:
```bash
php artisan make:model Thread -m
```

Abre la migración creada en `database/migrations/` y define los campos:
```php
public function up()
{
    Schema::create('threads', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('author')->default('Estudiante');
        $table->timestamps();
    });
}
```
Y ejecuta:
```bash
php artisan migrate
```

---

### 2. Crear el Controlador (`ForoController`)
Genera un controlador:
```bash
php artisan make:controller ForoController
```

Implementa la lógica en `app/Http/Controllers/ForoController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ForoController extends Controller
{
    public function index($activeId = null)
    {
        // Obtener todos los foros/hilos
        $threads = Thread::latest()->get();
        
        // Obtener el foro seleccionado o el más reciente por defecto
        $activeThread = $activeId 
            ? Thread::findOrFail($activeId) 
            : Thread::latest()->first();

        return view('foro.index', compact('threads', 'activeThread'));
    }

    public function create()
    {
        return view('foro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'institucion' => 'required|string',
            'carrera' => 'required|string',
            'tipo_ayuda' => 'required|string',
            'duracion' => 'required|string',
            'condiciones' => 'required|string',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        // Guardar la oportunidad/foro
        $thread = new Thread();
        $thread->title = 'Beca ' . $request->tipo_ayuda . ' (' . $request->institucion . '): ' . $request->carrera;
        $thread->content = $request->descripcion . "\n\nDuración: " . $request->duracion . "\nCondiciones: " . $request->condiciones;
        $thread->save();

        return redirect()->route('foro.index')->with('success', 'Oportunidad creada con éxito.');
    }
}
```

---

### 3. Definir las Rutas
Abre `routes/web.php` y registra las rutas:
```php
use App\Http\Controllers\ForoController;

Route::get('/foro', [ForoController::class, 'index'])->name('foro.index');
Route::get('/foro/create', [ForoController::class, 'create'])->name('foro.create');
Route::post('/foro', [ForoController::class, 'store'])->name('foro.store');
Route::get('/foro/{activeId}', [ForoController::class, 'index'])->name('foro.show');
```

---

## Atributos de Diseño de Figma Aplicados
*   **Tipografía:** Cargado de fuentes Google: `Inria Sans` (títulos y acentos), `Spline Sans` (estructural) y `Nunito` (cuerpo de texto).
*   **Colores exactos:** Implementados mediante variables CSS (`:root`), incluyendo el azul marino profundo (`#000422`), el azul brillante de los botones (`#018cf5`), el azul del banner (`#0059ff`) y el amarillo/oro de acentos (`#ffc300`).
*   **Diseño Responsivo:** En pantallas de escritorio se visualiza en dos columnas para el listado, y una columna estrecha centrada para el formulario. En tablets y móviles, todos los elementos se apilan correctamente.

