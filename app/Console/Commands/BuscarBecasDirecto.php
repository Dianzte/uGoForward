<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\GeminiBecaExtractorService; 
use App\Models\Universidad;
use Carbon\Carbon;

class BuscarBecasDirecto extends Command
{
    /**
     * Firma del comando en la terminal.
     */
    protected $signature = 'becas:buscar-directo';

    /**
     * Descripción del comando.
     */
    protected $description = 'Realiza scraping directo a portales web oficiales de El Salvador y procesa con Gemini AI';

    /**
     * Fuentes directas de El Salvador a rastrear.
     */
    protected array $fuentes = [
        
        
       [
            'nombre' => 'UCA (Universidad Centroamericana José Simeón Cañas)',
            'url'    => 'https://www.uca.edu.sv/becas',
            'selector_base' => 'a',
        ],
        [
            'nombre' => 'universidadesSV',
            'url'    => 'https://universidades.sv/',
            'selector_base' => 'a',
        ],
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
    ];

    /**
     * Palabras clave para filtrar enlaces relevantes sobre becas.
     */
    protected array $palabrasClave = [
        'beca', 'becas', 'convocatoria', 'estudio', 'posgrado', 'pregrado',
        'maestria', 'maestría', 'doctorado', 'financiamiento', 'fantel',
        'ayuda economica', 'ayuda económica', 'educacion superior', 'educación superior'
    ];

    /**
     * Ejecución del comando pasando el servicio de Gemini por inyección de dependencias.
     */
    public function handle(GeminiBecaExtractorService $geminiService): int
    {
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

    private function obtenerTextoDePagina(string $url, string $textoEnlace): string
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; UGF-Becas/1.0)',
            ])->timeout(15)->get($url);

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