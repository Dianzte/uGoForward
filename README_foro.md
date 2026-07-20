# Integración del Foro Estudiantil

Este directorio contiene los archivos estructurados para la pantalla de **Foro Estudiantil** (basada en el diseño Figma del proyecto **CreaJ MP**).

## Archivos Instalados
*   **Vista Blade:** [resources/views/foro.blade.php](file:///C:/laragon/www/ugf/resources/views/foro.blade.php)
*   **Estilos CSS:** [public/css/foro.css](file:///C:/laragon/www/ugf/public/css/foro.css)

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

        return view('foro', compact('threads', 'activeThread'));
    }
}
```

---

### 3. Definir las Rutas
Abre `routes/web.php` y registra la ruta:
```php
use App\Http\Controllers\ForoController;

Route::get('/foro/{activeId?}', [ForoController::class, 'index'])->name('foro.index');
```

---

## Atributos de Diseño de Figma Aplicados
*   **Tipografía:** Cargado de fuentes Google: `Inria Sans` (títulos y acentos), `Spline Sans` (estructural) y `Nunito` (cuerpo de texto).
*   **Colores exactos:** Implementados mediante variables CSS (`:root`), incluyendo el azul marino profundo (`#000422`), el azul brillante de los botones (`#018cf5`), el azul del banner (`#0059ff`) y el amarillo/oro de acentos (`#ffc300`).
*   **Diseño Responsivo:** En pantallas de escritorio se visualiza en dos columnas. En tablets y móviles, el menú lateral de hilos se apila automáticamente debajo del contenido principal.
