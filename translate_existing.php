<?php

use Illuminate\Support\Facades\Http;
use App\Models\Beca;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function translateText($text) {
    if (empty($text)) return null;
    try {
        $response = Http::get('https://translate.googleapis.com/translate_a/single', [
            'client' => 'gtx',
            'sl' => 'es',
            'tl' => 'en',
            'dt' => 't',
            'q' => $text
        ]);
        
        $json = $response->json();
        $translatedText = '';
        if (isset($json[0]) && is_array($json[0])) {
            foreach ($json[0] as $segment) {
                if (isset($segment[0])) {
                    $translatedText .= $segment[0];
                }
            }
        }
        return $translatedText ?: null;
    } catch (\Exception $e) {
        return null;
    }
}

$becas = Beca::all();
foreach ($becas as $beca) {
    echo "Traduciendo beca ID: {$beca->id}...\n";
    
    $beca->titulo_en = translateText($beca->titulo);
    $beca->descripcion_en = translateText($beca->descripcion);
    $beca->pais_destino_en = translateText($beca->pais_destino);
    $beca->nivel_academico_en = translateText($beca->nivel_academico);
    $beca->modalidad_en = translateText($beca->modalidad);
    $beca->cobertura_resumen_en = translateText($beca->cobertura_resumen);
    
    $beca->save();
}

echo "¡Traducción de becas existentes completada!\n";
