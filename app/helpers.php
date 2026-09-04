<?php

use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;

if (!function_exists('translate_db')) {
    function translate_db($text)
    {
        if (empty($text)) {
            return $text;
        }

        $targetLocale = app()->getLocale();

        // Si el locale es español (o default) por ejemplo, pero queremos asegurarnos
        // de que se muestre en el idioma correcto, Stichoza puede detectar el origen
        // automáticamente si pasamos 'null' como idioma de origen.
        
        $cacheKey = 'trans_' . md5($text) . '_' . $targetLocale;

        return Cache::rememberForever($cacheKey, function () use ($text, $targetLocale) {
            try {
                $tr = new GoogleTranslate();
                // Origen detectado automáticamente, destino el locale actual
                $tr->setSource(); 
                $tr->setTarget($targetLocale);
                return $tr->translate($text);
            } catch (\Exception $e) {
                // Si falla la traducción, retorna el texto original
                return $text;
            }
        });
    }
}

if (!function_exists('translate_array')) {
    function translate_array($items): array
    {
        if (empty($items)) {
            return [];
        }

        // Si llega como JSON string (por si acaso el cast no se aplicó), decodificarlo
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            $items = is_array($decoded) ? $decoded : [$items];
        }

        return array_map(fn($item) => translate_db((string) $item), $items);
    }
}
