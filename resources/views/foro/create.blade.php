<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Oportunidad - UGF</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@400;700&family=Nunito:wght@300;400;600;700&family=Spline+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/foro/create.css'])
</head>

<body>

    <header class="foro-header">
        <div class="header-waves">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#0059ff" fill-opacity="1"
                    d="M0,192L48,181.3C96,171,192,149,288,144C384,139,480,149,576,176C672,203,768,245,864,245.3C960,245,1056,203,1152,176C1248,149,1344,139,1392,133.3L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
                </path>
            </svg>
        </div>

        <div class="container header-container">
            <!-- Brand Logo (Group 38 in Figma) -->
            <div class="brand-logo">
                <div class="logo-circle"></div>
                <span class="logo-text">UGF</span>
            </div>

            <!-- Header Titles -->
            <div class="header-titles">
                <span class="subtitle">Foro estudiantil</span>
                <h1 class="main-title">U Go Forward</h1>
            </div>

            <!-- Ship Icon Container (Barquito in Figma) -->
            <div class="ship-decoration">
                <svg viewBox="0 0 100 100" class="ship-svg">
                    <path d="M20 70 L80 70 L70 85 L30 85 Z" fill="#ffffff" />
                    <path d="M48 20 L48 65 L25 65 Z" fill="#ffc300" />
                    <path d="M52 15 L52 65 L75 65 Z" fill="#ffffff" fill-opacity="0.8" />
                    <line x1="50" y1="10" x2="50" y2="70" stroke="#ffffff"
                        stroke-width="2" />
                </svg>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="foro-main">
        <div class="container form-main-container">

            <!-- Centered Form Card (Rectangle 26 theme scaled) -->
            <section class="form-container-card">
                <div class="thread-card form-card">
                    <div class="thread-header form-header-text">
                        <span class="thread-meta">Comparte tus conocimientos</span>
                        <h2 class="thread-title">Crea un nuevo grupo</h2>
                    </div>

                    <!-- Form submission -->
                    <form action="{{ route('foro.store') }}" method="POST" enctype="multipart/form-data" class="oportunidad-form">
                        @csrf

                        <!-- Form Grid Layout -->
                        <div class="form-grid">

                            <div class="form-group full-width">
                                <label for="titulo">Título</label>
                                <input type="text" id="titulo" name="titulo" placeholder="¿Cómo es la vida universitaria?"
                                    value="{{ old('titulo') }}" class="@error('titulo') is-invalid @enderror">
                                @error('titulo')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="universidad_id">Institución educativa</label>
                                <select name="universidad_id" id="universidad_id"
                                    class="@error('universidad_id') is-invalid @enderror">
                                    <option value="">Selecciona una universidad</option>
                                    @foreach ($universidades as $universidad)
                                        <option value="{{ $universidad->id }}"
                                            {{ old('universidad_id') == $universidad->id ? 'selected' : '' }}>
                                            {{ $universidad->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('universidad_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="carrera_id">Carrera</label>
                                <select name="carrera_id" id="carrera_id" class="@error('carrera_id') is-invalid @enderror">
                                    <option value="">Selecciona una carrera</option>
                                    @foreach ($carreras as $carrera)
                                        <option value="{{ $carrera->id }}"
                                            {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('carrera_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="categoriaforo_id">Categoría</label>
                                <select name="categoriaforo_id" id="categoriaforo_id"
                                    class="@error('categoriaforo_id') is-invalid @enderror">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoriaforo_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->categorias }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoriaforo_id')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="contenido">Descripción</label>
                                <textarea id="contenido" name="contenido" rows="4" required placeholder="Escribe detalles del foro..."
                                    class="@error('contenido') is-invalid @enderror">{{ old('contenido') }}</textarea>
                                @error('contenido')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('foro.index') }}" class="btn-cancel">Cancelar</a>
                            <button type="submit" class="btn-submit">Crear</button>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer decoration (ondaOla at the bottom) -->
    <footer class="foro-footer">
        <div class="footer-waves">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#0059ff" fill-opacity="1"
                    d="M0,224L48,202.7C96,181,192,139,288,144C384,149,480,203,576,218.7C672,235,768,213,864,186.7C960,160,1056,128,1152,122.7C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
        <div class="container footer-content">
            <p>&copy; {{ date('Y') }} UGF - Mar de Oportunidades.</p>
        </div>
    </footer>

   
</body>

</html>
