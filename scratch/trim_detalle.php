<?php
$file = __DIR__ . '/../resources/views/becas/detalle.blade.php';
$content = file_get_contents($file);
$marker = '@endpush';
// Find the FIRST @endpush (after the good @push block)
$pos = strpos($content, $marker);
if ($pos !== false) {
    $trimmed = substr($content, 0, $pos + strlen($marker)) . "\n";
    file_put_contents($file, $trimmed);
    echo "OK - file trimmed to " . strlen($trimmed) . " bytes, " . substr_count($trimmed, "\n") . " lines\n";
} else {
    echo "ERROR: @endpush not found\n";
}
