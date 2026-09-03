<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {  
        $request->validate([
            'message' => 'required|string',
            'history' => 'array'
        ]);

        $message = $request->input('message');
        $history = $request->input('history', []);

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'La API Key de Gemini no está configurada.'], 500);
        }

        // Preparar el System Prompt para GUAYABOT
        $systemInstruction = [
            "parts" => [
                ["text" => "Eres GUAYABOT, el asistente virtual y consejero vocacional del sitio web uGoForward. Ayudas a estudiantes a descubrir qué estudiar, elegir la mejor universidad y encontrar becas disponibles. Eres empático, amigable, claro y motivador."]
            ]
        ];

        // Formatear el historial y el mensaje nuevo para Gemini
        $contents = [];
        
        foreach ($history as $msg) {
            $contents[] = [
                "role" => $msg['role'] === 'user' ? 'user' : 'model',
                "parts" => [["text" => $msg['text']]]
            ];
        }

        // Agregar el mensaje actual del usuario
        $contents[] = [
            "role" => "user",
            "parts" => [["text" => $message]]
        ];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, [
                "systemInstruction" => $systemInstruction,
                "contents" => $contents,
                "generationConfig" => [
                    "temperature" => 0.7,
                    "maxOutputTokens" => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '¡Hola! Hubo un problema al procesar mi respuesta.';
                
                return response()->json(['reply' => $reply]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json(['error' => 'No pudimos conectar con GUAYABOT en este momento. Intenta de nuevo más tarde. (Error: ' . $response->status() . ')'], 500);

        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error inesperado al conectar con GUAYABOT.'], 500);
        }
    }
}
