<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Testsocioemocionalresultado;

class TestSocioemocionalController extends Controller
{
    public function index()
    {
        return view('estudiante.test-socioemocional');
    }

    public function guardar(Request $request)
    {
        $answers = $request->input('answers', []);
        $reflection = $request->input('reflection', '');

        $preguntas = [
            1 => '¿Disfrutas reparar o construir cosas con tus manos?',
            2 => '¿Te interesa trabajar con herramientas, máquinas o vehículos?',
            3 => '¿Prefieres actividades al aire libre en vez de estar en una oficina?',
            4 => '¿Te gusta investigar por qué ocurren las cosas antes de actuar?',
            5 => '¿Disfrutas resolver problemas lógicos o matemáticos?',
            6 => '¿Te consideras una persona curiosa que hace muchas preguntas?',
            7 => '¿Te gusta expresarte a través del arte, la música o la escritura?',
            8 => '¿Prefieres tareas donde puedas ser original en lugar de seguir un manual?',
            9 => '¿Disfrutas imaginar ideas o soluciones poco convencionales?',
            10 => '¿Te motiva ayudar a otras personas a resolver sus problemas?',
            11 => '¿Disfrutas enseñar, explicar o guiar a otros?',
            12 => '¿Te consideras una persona empática que escucha bien a los demás?',
            13 => '¿Te gusta convencer o influir en las decisiones de otras personas?',
            14 => '¿Disfrutas liderar proyectos o tomar la iniciativa en grupo?',
            15 => '¿Te sientes cómodo tomando decisiones bajo presión?',
            16 => '¿Prefieres seguir procesos claros y ordenados?',
            17 => '¿Te gusta organizar información, datos o archivos?',
            18 => '¿Disfrutas revisar detalles para asegurarte de que todo esté correcto?',
        ];

        $historialRespuestas = [];
        foreach ($answers as $id => $val) {
            $textoPregunta = $preguntas[$id] ?? "Pregunta {$id}";
            $historialRespuestas[] = "Pregunta: '{$textoPregunta}' -> Puntuación (1-5): {$val}";
        }

        $prompt = "Actúa como un orientador vocacional y psicólogo educativo profesional para estudiantes en El Salvador. "
            . "Analiza las siguientes respuestas:\n\n"
            . implode("\n", $historialRespuestas) . "\n\n"
            . "Reflexión: " . ($reflection ?: 'Ninguna') . "\n\n"
            . "Genera una recomendación estructurada en JSON con exactamente estas claves:\n"
            . "{\n"
            . '  "afinidad": 85,' . "\n"
            . '  "carrera_principal": "Nombre de la carrera sugerida",' . "\n"
            . '  "razonamiento": "Explicación breve y motivadora.",' . "\n"
            . '  "fortalezas_detectadas": ["Fortaleza 1", "Fortaleza 2", "Fortaleza 3"],' . "\n"
            . '  "universidades_sugeridas": [' . "\n"
            . '    {"nombre": "Universidad de El Salvador (UES)", "detalle": "Pública — Excelente en ciencias"}' . "\n"
            . '  ],' . "\n"
            . '  "carreras_alternativas": [' . "\n"
            . '    {"nombre": "Carrera alternativa 1", "motivo": "Motivo breve"}' . "\n"
            . '  ]' . "\n"
            . "}";

 $apiKey = env('GEMINI_API_KEY');

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}";

$response = Http::post($url, [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ],
    'generationConfig' => [
        'response_mime_type' => 'application/json'
    ]
]);
        if ($response->successful()) {
            $data = $response->json();
            $textoIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $resultadoIA = json_decode($textoIA, true);

            // Guardar el resultado en la base de datos si el usuario inició sesión
            if (Auth::check()) {
                Testsocioemocionalresultado::create([
                    'user_id' => Auth::id(),
                    'carrera_principal' => $resultadoIA['carrera_principal'] ?? null,
                    'afinidad' => $resultadoIA['afinidad'] ?? null,
                    'razonamiento' => $resultadoIA['razonamiento'] ?? null,
                    'resultado_json' => json_encode($resultadoIA),
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $resultadoIA
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error de la API: ' . $response->body()
        ], 500);
    }
}