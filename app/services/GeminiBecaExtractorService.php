<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiBecaExtractorService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        // Utilizando la versión v1beta que soporta responseSchema
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent';
    }

    /**
     * Analiza el texto de una URL o afiche y devuelve un array asociativo con los datos de la beca.
     */
    public function extraerDatosDeTexto(string $textoFuente, string $urlFuente): ?array
    {
    if (empty($this->apiKey)) {
        Log::error('GEMINI_API_KEY no está configurada.');
        return null;
    }

    $prompt = <<<PROMPT
        Eres un asistente especializado en identificar oportunidades de becas relevantes para estudiantes de El Salvador.
        Analiza el contenido completo de la página web ($urlFuente) y extrae únicamente datos que estén respaldados por el texto.

        REGLAS DE FILTRADO:
        1. Si el texto NO corresponde a una beca, ayuda financiera o convocatoria educativa para personas de El Salvador (por ejemplo, licitación, empleo, evento o publicidad), coloca "es_beca_nacional": false.
        2. Una beca para estudiar en otro país puede ser válida si está dirigida a salvadoreños; en ese caso coloca "es_beca_nacional": true y registra el país destino.
        3. No inventes datos: usa null, [] o "No especificado" cuando un dato no aparezca.
        4. Normaliza el nombre de la institución u oferente (Ejemplos: "Universidad de El Salvador", "MINED", "ESCO", "FANTEL", "Universidad Don Bosco").
PROMPT;

    $response = Http::withoutVerifying()->withHeaders([
        'Content-Type' => 'application/json',
    ])->post("{$this->apiUrl}?key={$this->apiKey}", [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt . "\n\nTexto de la fuente:\n" . substr($textoFuente, 0, 12000)]
                ]
            ]
        ],
        'generationConfig' => [
            'responseMimeType' => 'application/json',
            'responseSchema'   => $this->obtenerEsquemaJson(),
            'temperature'      => 0.2,
        ]
    ]);

    // SI LA PETICIÓN FALLA, IMPRIMIR EL MOTIVO EXACTO
    if ($response->failed()) {
        Log::error('Error HTTP de Gemini: ' . $response->status() . ' - ' . $response->body());
        // Imprimir error en pantalla durante la ejecución
        echo "\n      [!] Error de API Gemini ({$response->status()}): " . $response->json('error.message', $response->body()) . "\n";
        return null;
    }

    $jsonRaw = $response->json('candidates.0.content.parts.0.text');

    if (empty($jsonRaw)) {
        Log::error('Gemini devolvió una respuesta vacía: ' . $response->body());
        return null;
    }

    return json_decode($jsonRaw, true);
}

    /**
     * Define el esquema JSON que Gemini DEBE respetar obligatoriamente.
     */
    private function obtenerEsquemaJson(): array
    {
        return [
            'type' => 'OBJECT',
            'properties' => [
                'es_beca_nacional' => [
                    'type' => 'BOOLEAN',
                    'description' => 'Indica si el texto describe una beca o ayuda para estudiar en El Salvador. true si lo es, false si es otra cosa (aviso laboral, licitación, etc.).'
                ],
                'titulo_convocatoria' => [
                    'type' => 'STRING',
                    'description' => 'Nombre oficial de la beca o programa de estudio'
                ],
                'institucion_oferente' => [
                    'type' => 'STRING',
                    'description' => 'País, universidad u organización que ofrece la beca (ej: OEA, Corea, ESCO)'
                ],
                'pais_destino' => [
                    'type' => 'STRING',
                    'description' => 'País donde se realizarán los estudios'
                ],
                'carreras_o_areas' => [
                    'type' => 'ARRAY',
                    'items' => ['type' => 'STRING'],
                    'description' => 'Carreras, áreas de estudio o perfiles académicos elegibles'
                ],
                'cum_promedio_minimo' => [
                    'type' => 'STRING',
                    'description' => 'CUM, promedio mínimo o requisito académico equivalente',
                    'nullable' => true
                    ],
                'nivel_academico' => [
                    'type' => 'STRING',
                    'description' => 'Pregrado, Maestría, Doctorado, Curso Corto, etc.'
                ],
                'modalidad' => [
                    'type' => 'STRING',
                    'description' => 'Presencial, Virtual o Híbrida'
                ],
                'fecha_cierre_postulacion' => [
                    'type' => 'STRING',
                    'description' => 'Fecha límite para enviar papeles en formato YYYY-MM-DD si es posible'
                ],
                'cobertura' => [
                    'type' => 'STRING',
                    'description' => 'Resumen de lo que cubre (100% matrícula, pasajes, estipendio mensual, etc.)'
                ],
                'requisitos_clave' => [
                    'type' => 'ARRAY',
                    'items' => ['type' => 'STRING'],
                    'description' => 'Lista de los requisitos más importantes requeridos (ej: CUM de 8.0, Título, TOEFL)'
                ],
            ],
            'required' => ['es_beca_nacional', 'titulo_convocatoria', 'institucion_oferente', 'nivel_academico', 'carreras_o_areas', 'requisitos_clave']
        ];
    }
}