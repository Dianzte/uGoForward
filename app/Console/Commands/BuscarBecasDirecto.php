<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\GeminiBecaExtractorService; 
use App\Models\Universidad;
use App\Models\Beca;
use Carbon\Carbon;

class BuscarBecasDirecto extends Command
{
    /**
     * Firma del comando en la terminal.
     */
    protected $signature = 'becas:buscar-directo
                            {--objetivo=20 : Cantidad de becas nuevas que se intentara guardar}
                            {--profundidad=2 : Profundidad maxima de enlaces internos a explorar}
                            {--lote=5 : Cantidad de becas nuevas por lote informativo}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Realiza scraping directo a portales web oficiales de El Salvador y procesa con Gemini AI';

    /**
     * Fuentes directas de El Salvador a rastrear.
     */
    protected array $fuentes = [

        

        
        [
            'nombre' => 'Programa Oportunidades  Fundación Gloria de Kriete',
            'url'    => 'https://www.oportunidades.org.sv/',
            'selector_base' => 'a',
        ],
        [
            'nombre' => 'Fundación Poma',
            'url'    => 'https://fundacionpoma.org/',
            'selector_base' => 'a',
        ],
         [
            'nombre' => 'Programa ¡Supérate!',
            'url'    => 'https://superate.org.sv/',
            'selector_base' => 'a',
        ],
         [
            'nombre' => 'Universidad Don Bosco (UDB)',
            'url'    => 'https://www.udb.edu.sv/udb/pagina/proyeccion_social_becas',
            'selector_base' => 'a',
        ],
    [
        'nombre'        => 'Universidad Centroamericana José Simeón Cañas (UCA)',
        'url'           => 'https://uca.edu.sv/becas/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Universidad Tecnológica de El Salvador (UTEC)',
        'url'           => 'https://www.utec.edu.sv/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Universidad Francisco Gavidia (UFG)',
        'url'           => 'https://www.ufg.edu.sv/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Universidad Evangélica de El Salvador (UEES)',
        'url'           => 'https://uees.edu.sv/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Universidad Católica de El Salvador (UNICAES)',
        'url'           => 'https://www.catolica.edu.sv/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Escuela Agrícola Panamericana Zamorano',
        'url'           => 'https://www.zamorano.edu/admisiones/asistencia-financiera/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Escuela de Comunicación Mónica Herrera (ECMH)',
        'url'           => 'https://monicaherrera.edu.sv/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'FANTEL / Ministerio de Educación (MINED)',
        'url'           => 'https://www.mined.gob.sv/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'FEPADE - Becas Edubecas',
        'url'           => 'https://fepade.org.sv/edubecas/',
        'selector_base' => 'a',
    ],
    [
        'nombre'        => 'Organización de los Estados Americanos (OEA - Becas)',
        'url'           => 'https://www.oas.org/es/becas/',
        'selector_base' => 'a',
    ],
    [
            'nombre' => 'universidadesSV',
            'url'    => 'https://universidades.sv/',
            'selector_base' => 'a',
        ]
    ];

    /**
     * Palabras clave para filtrar enlaces relevantes sobre becas.
     */
    protected array $palabrasClave = [
        'beca', 'becas', 'convocatoria', 'estudio', 'posgrado', 'pregrado',
        'maestria', 'maestría', 'doctorado', 'financiamiento', 'fantel',
        'ayuda economica', 'ayuda económica', 'educacion superior', 'educación superior',
        'scholarship', 'fellowship', 'financial aid', 'admission', 'admisiones',
        'postulacion', 'postulación', 'apply', 'oportunidad', 'programa'
    ];

    /**
     * Ejecución del comando pasando el servicio de Gemini por inyección de dependencias.
     */
    public function handle(GeminiBecaExtractorService $geminiService): int
    {
        return $this->handleExpanded($geminiService);

        $this->info("Iniciando rastreo directo en sitios oficiales de El Salvador...");
        $this->newLine();

        $totalEncontrados = 0;

        foreach ($this->fuentes as $fuente) {
            $this->warn("Rastreando: {$fuente['nombre']} ({$fuente['url']})...");

            try {
                // 1. Obtener HTML del sitio web
                $response = Http::withoutVerifying()->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ])->timeout(15)->get($fuente['url']);

                if ($response->failed()) {
                    $this->error("No se pudo acceder a {$fuente['url']} (Código: {$response->status()})");
                    continue;
                }

                $html = $response->body();
                $crawler = new Crawler($html, $fuente['url']);
                $enlacesProcesados = 0;

                    // 2. Filtrar enlaces relevantes del HTML
                    $urlsVisitadas = [];
                    $crawler->filter($fuente['selector_base'])->each(function (Crawler $node) use ($totalEncontrados, &$enlacesProcesados, &$urlsVisitadas, $fuente, $geminiService) {
                        $texto = trim($node->text());

                        // Obtener el texto del elemento padre directo de forma segura
                        $contexto = $node->ancestors()->count() > 0 
                            ? trim($node->ancestors()->first()->text('')) 
                            : $texto;

                        $url = $node->attr('href');

                        if (empty($url) || empty($texto)) {
                            return;
                        }

                    $url = $this->resolverUrl($url, $fuente['url']);

                    // Si el enlace coincide con términos de becas
                    if ($this->esEnlaceDeBeca($texto . ' ' . $contexto, $url)) {
                        if (isset($urlsVisitadas[$url])) {
                            return;
                        }
                        $urlsVisitadas[$url] = true;

                        $this->line("  <comment>[+] Enlace detectado:</comment> {$texto}");
                        $this->line("      <info>URL:</info> {$url}");

                        // 3. ENVIAR A GEMINI AI PARA EXTRAER DATOS EN JSON
                        $this->line("      <comment>---> Analizando contenido con Gemini AI...</comment>");
                        
                        $contenido = $this->obtenerTextoDePagina($url, $texto . "\n" . $contexto);
                        $datosEstructurados = $geminiService->extraerDatosDeTexto($contenido, $url);

                        if ($datosEstructurados) {
                            // 1. Filtrar si no es una beca de estudio nacional
                            if (isset($datosEstructurados['es_beca_nacional']) && !$datosEstructurados['es_beca_nacional']) {
                                $this->warn("      [-] Ignorado: No corresponde a una beca de estudio nacional.");
                                $this->line("      esperando 30 segundos para respetar el límite de cuota...");
                                sleep(30);
                                return;
                            }

                            // Mostrar tabla resumida en la terminal
                            $this->table(
                                ['Campo', 'Información Extraída por AI'],
                                [
                                    ['Título', $datosEstructurados['titulo_convocatoria'] ?? 'N/A'],
                                    ['Oferente', $datosEstructurados['institucion_oferente'] ?? 'N/A'],
                                    ['Nivel', $datosEstructurados['nivel_academico'] ?? 'N/A'],
                                    ['Cierre', $datosEstructurados['fecha_cierre_postulacion'] ?? 'No especificada'],
                                    ['Cobertura', $datosEstructurados['cobertura'] ?? 'N/A'],
                                ]);

                                $universidad = Universidad::firstOrCreate(
                                ['nombre_completo' => trim($datosEstructurados['institucion_oferente'] ?? 'N/A')],
                                ['siglas' => strtok($datosEstructurados['institucion_oferente'] ?? 'N/A', " ")]);

                                // 2. Sanitizar campos vacíos para evitar errores SQL (ej. fecha vacía)
                                $vencimiento = $this->normalizarFecha($datosEstructurados['fecha_cierre_postulacion'] ?? null);
                                $cumMinimo = !empty($datosEstructurados['cum_promedio_minimo']) ? trim($datosEstructurados['cum_promedio_minimo']) : null;

                                \App\Models\Beca::updateOrCreate(
                                ['url_oficial' => $url], 
                                [
                                    'titulo'              => $datosEstructurados['titulo_convocatoria'],
                                    'universidad_id'      => $universidad->id, 
                                    'pais_destino'        => $datosEstructurados['pais_destino'] ?? null,
                                    'nivel_academico'     => $datosEstructurados['nivel_academico'] ?? 'Grado/Técnico',
                                    'modalidad'           => $datosEstructurados['modalidad'] ?? 'Presencial',
                                    'cobertura_resumen'   => $datosEstructurados['cobertura'] ?? null,
                                    'requisitos'          => $datosEstructurados['requisitos_clave'] ?? [], 
                                    'carreras_cobertura'  => $datosEstructurados['carreras_o_areas'] ?? [],  
                                    'cum_promedio_minimo' => $cumMinimo,
                                    'vencimiento'         => $vencimiento,
                                    'descripcion'         => $datosEstructurados['cobertura'] ?? 'Sin descripción detallada',
                                    'estado'              => 'Activa',
                                ]);
                                $this->info("      [✓] Beca guardada exitosamente");
                                sleep(4);
                        } else {
                            $this->error("      [X] No se pudieron estructurar datos con Gemini.");
                        }

                        $this->line("      esperando 20 segundos para respetar el límite de cuota...");
                        sleep(20); 
                        $totalEncontrados++;
                        $enlacesProcesados++;
                    }
                });

                if ($enlacesProcesados === 0) {
                    $this->line("  No se encontraron convocatorias visibles en la página principal.");
                }

            } catch (\Exception $e) {
                $this->error("Error al rastrear {$fuente['nombre']}: " . $e->getMessage());
                Log::error("Error de Scraping en {$fuente['nombre']}", ['error' => $e->getMessage()]);
            }

            $this->newLine();
        }

        $this->info("Rastreo finalizado. Total de becas procesadas con AI: {$totalEncontrados}");

        return Command::SUCCESS;
    }

    private function handleExpanded(GeminiBecaExtractorService $geminiService): int
    {
        $objetivo = max(1, (int) $this->option('objetivo'));
        $profundidadMaxima = max(0, (int) $this->option('profundidad'));
        $tamanoLote = max(1, (int) $this->option('lote'));
        $totalNuevas = 0;
        $nuevasEnLote = 0;

        $this->info("Iniciando rastreo profundo con objetivo de {$objetivo} becas nuevas y lotes de {$tamanoLote}...");

        foreach ($this->fuentes as $fuente) {
            if ($totalNuevas >= $objetivo) {
                break;
            }

            $this->warn("Rastreando: {$fuente['nombre']} ({$fuente['url']})...");
            $cola = [[$fuente['url'], 0]];
            $visitadas = [];
            $candidatas = [];

            while ($cola && $totalNuevas < $objetivo) {
                [$paginaUrl, $nivel] = array_shift($cola);
                $paginaUrl = $this->normalizarUrl($paginaUrl);
                if (isset($visitadas[$paginaUrl])) {
                    continue;
                }
                $visitadas[$paginaUrl] = true;

                $html = $this->obtenerHtml($paginaUrl);
                if ($html === null) {
                    continue;
                }

                $crawler = new Crawler($html, $paginaUrl);
                $crawler->filter($fuente['selector_base'])->each(function (Crawler $node) use (&$candidatas, &$cola, $paginaUrl, $nivel, $fuente, $profundidadMaxima) {
                    $href = trim((string) $node->attr('href'));
                    if ($href === '') {
                        return;
                    }

                    $url = $this->normalizarUrl($this->resolverUrl($href, $paginaUrl));
                    if (!$this->esMismoDominio($url, $fuente['url']) || !$this->esUrlNavegable($url)) {
                        return;
                    }

                    $texto = trim($node->text(''));
                    $contexto = $node->ancestors()->count() > 0
                        ? trim($node->ancestors()->first()->text(''))
                        : $texto;
                    $evidencia = $texto . ' ' . $contexto . ' ' . $url;

                    if ($this->esEnlaceDeBeca($evidencia, $url) || $this->esDocumento($url)) {
                        $candidatas[$url] = trim($texto . "\n" . $contexto);
                    }

                    if ($nivel < $profundidadMaxima && !isset($candidatas[$url])) {
                        $cola[] = [$url, $nivel + 1];
                    }
                });

                $this->line("  Explorando nivel {$nivel}: {$paginaUrl}");
                foreach ($candidatas as $url => $contexto) {
                    if ($totalNuevas >= $objetivo) {
                        break 2;
                    }
                    unset($candidatas[$url]);
                    if (Beca::where('url_oficial', $url)->exists()) {
                        continue;
                    }
                    if ($this->procesarCandidata($url, $contexto, $geminiService)) {
                        $totalNuevas++;
                        $nuevasEnLote++;

                        if ($nuevasEnLote >= $tamanoLote) {
                            $this->info("Lote completado: {$nuevasEnLote} becas nuevas guardadas. Continuando búsqueda...");
                            $nuevasEnLote = 0;
                        }
                    }
                }
            }
        }

        $this->info("Rastreo finalizado. Total de becas nuevas guardadas: {$totalNuevas}");
        return Command::SUCCESS;
    }

    private function obtenerHtml(string $url): ?string
    {
        try {
            $response = Http::withoutVerifying()
                ->retry(3, 2000)
                ->timeout(60)
                ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; UGF-Becas/1.0)',
                ])
                ->get($url);
            return $response->successful() ? $response->body() : null;
        } catch (\Throwable $exception) {
            Log::warning('No se pudo rastrear la pagina', ['url' => $url, 'error' => $exception->getMessage()]);
            return null;
        }
    }

    private function procesarCandidata(string $url, string $contexto, GeminiBecaExtractorService $geminiService): bool
    {
        $this->line("  <comment>[+] Candidata:</comment> {$url}");
        $datos = $geminiService->extraerDatosDeTexto($this->obtenerTextoDePagina($url, $contexto), $url);

        if (!$datos || (($datos['es_beca_nacional'] ?? false) !== true)) {
            return false;
        }

        $titulo = trim((string) ($datos['titulo_convocatoria'] ?? ''));
        $oferente = trim((string) ($datos['institucion_oferente'] ?? ''));
        if ($titulo === '' || $oferente === '' || $titulo === 'No especificado') {
            return false;
        }

        $universidad = $this->obtenerUniversidad($oferente);
        if (!$universidad) {
            return false;
        }

        $duplicada = Beca::where('url_oficial', $url)
            ->orWhere(function ($consulta) use ($titulo, $universidad) {
                $consulta->where('universidad_id', $universidad->id)
                    ->whereRaw('LOWER(titulo) = ?', [mb_strtolower($titulo)]);
            })
            ->exists();

        if ($duplicada) {
            $this->line("      [-] Beca repetida ignorada: {$titulo}");
            return false;
        }

        try {
            Beca::create([
            'url_oficial' => $url,
            'titulo' => $titulo,
            'universidad_id' => $universidad->id,
            'pais_destino' => $datos['pais_destino'] ?? null,
            'nivel_academico' => $datos['nivel_academico'] ?? 'Grado/Técnico',
            'modalidad' => $datos['modalidad'] ?? 'Presencial',
            'cobertura_resumen' => $datos['cobertura'] ?? null,
            'requisitos' => $datos['requisitos_clave'] ?? [],
            'carreras_cobertura' => $datos['carreras_o_areas'] ?? [],
            'cum_promedio_minimo' => !empty($datos['cum_promedio_minimo']) ? trim($datos['cum_promedio_minimo']) : null,
            'vencimiento' => $this->normalizarFecha($datos['fecha_cierre_postulacion'] ?? null),
            'descripcion' => $datos['cobertura'] ?? 'Sin descripción detallada',
            'estado' => 'Activa',
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            Log::notice('Beca duplicada durante el rastreo; se omite', [
                'url' => $url,
                'titulo' => $titulo,
                'error' => $exception->getMessage(),
            ]);
            $this->line("      [-] Beca repetida ignorada: {$titulo}");
            return false;
        }

        $this->info("      [✓] Beca nueva guardada: {$titulo}");
        sleep(2);
        return true;
    }

    private function obtenerUniversidad(string $nombre): ?Universidad
    {
        $nombre = trim(preg_replace('/\s+/', ' ', $nombre));
        $universidad = Universidad::whereRaw('LOWER(nombre_completo) = ?', [mb_strtolower($nombre)])->first();
        if ($universidad) {
            return $universidad;
        }

        $base = $this->generarSiglas($nombre);
        $siglas = $base;
        $contador = 2;
        while (Universidad::where('siglas', $siglas)->exists()) {
            $siglas = $base . $contador++;
        }

        try {
            return Universidad::create([
                'nombre_completo' => $nombre,
                'siglas' => $siglas,
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return Universidad::whereRaw('LOWER(nombre_completo) = ?', [mb_strtolower($nombre)])->first();
        }
    }

    private function generarSiglas(string $nombre): string
    {
        $palabras = preg_split('/\s+/', trim($nombre)) ?: [];
        $ignoradas = ['de', 'del', 'la', 'las', 'el', 'los', 'y', 'e'];
        $siglas = '';
        foreach ($palabras as $palabra) {
            if (!in_array(mb_strtolower($palabra), $ignoradas, true)) {
                $siglas .= mb_strtoupper(mb_substr($palabra, 0, 1));
            }
        }

        return $siglas !== '' ? mb_substr($siglas, 0, 10) : 'INST';
    }

    /**
     * Evalúa si un texto o enlace contiene términos relacionados con becas.
     */
    private function esEnlaceDeBeca(string $texto, string $url): bool
    {
        $contenido = strtolower($texto . ' ' . $url);

        foreach ($this->palabrasClave as $palabra) {
            if (str_contains($contenido, $palabra)) {
                return true;
            }
        }

        return false;
    }

    private function esMismoDominio(string $url, string $fuente): bool
    {
        $dominio = strtolower((string) parse_url($url, PHP_URL_HOST));
        $dominioFuente = strtolower((string) parse_url($fuente, PHP_URL_HOST));
        return preg_replace('/^www\./', '', $dominio) === preg_replace('/^www\./', '', $dominioFuente);
    }

    private function esUrlNavegable(string $url): bool
    {
        $esquema = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        return in_array($esquema, ['http', 'https'], true) && !str_contains($url, '#');
    }

    private function esDocumento(string $url): bool
    {
        return (bool) preg_match('/\.(pdf|docx?|xlsx?)($|[?#])/i', $url);
    }

    private function normalizarUrl(string $url): string
    {
        $partes = parse_url($url);
        if (!$partes || empty($partes['host'])) {
            return $url;
        }

        $ruta = $partes['path'] ?? '/';
        $ruta = preg_replace('#/+#', '/', $ruta);
        $ruta = preg_replace('#/\.?/#', '/', $ruta);
        $ruta = preg_replace('#/[^/]+/\.\./#', '/', $ruta);
        $resultado = ($partes['scheme'] ?? 'https') . '://' . strtolower($partes['host']) . $ruta;
        if (!empty($partes['query'])) {
            $resultado .= '?' . $partes['query'];
        }
        return rtrim($resultado, '/') ?: $resultado;
    }

    private function obtenerTextoDePagina(string $url, string $textoEnlace): string
    {
        try {
            $response = Http::withoutVerifying()
                ->retry(3, 2000)
                ->timeout(60)
                ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; UGF-Becas/1.0)',
                ])
                ->get($url);

            if ($response->successful()) {
                $pagina = new Crawler($response->body(), $url);
                $pagina->filter('script, style, noscript, nav, footer, header')->each(
                    fn (Crawler $node) => $node->getNode(0)?->parentNode?->removeChild($node->getNode(0))
                );

                return trim($textoEnlace . "\n" . preg_replace('/\s+/', ' ', $pagina->filter('body')->text('')));
            }
        } catch (\Throwable $exception) {
            Log::warning('No se pudo obtener el detalle de la beca', ['url' => $url, 'error' => $exception->getMessage()]);
        }

        return $textoEnlace;
    }

    private function resolverUrl(string $enlace, string $base): string
    {
        if (str_starts_with($enlace, 'http://') || str_starts_with($enlace, 'https://')) {
            return $enlace;
        }

        $baseParts = parse_url($base);
        $origin = ($baseParts['scheme'] ?? 'https') . '://' . ($baseParts['host'] ?? '');

        if (str_starts_with($enlace, '//')) {
            return ($baseParts['scheme'] ?? 'https') . ':' . $enlace;
        }

        if (str_starts_with($enlace, '/')) {
            return rtrim($origin, '/') . $enlace;
        }

        $path = $baseParts['path'] ?? '/';
        $directory = str_ends_with($path, '/') ? $path : dirname($path) . '/';

        return rtrim($origin . '/' . ltrim($directory . $enlace, '/'), '/');
    }

    private function normalizarFecha(?string $fecha): ?string
    {
        if (empty($fecha)) {
            return null;
        }

        try {
            return Carbon::parse($fecha)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}