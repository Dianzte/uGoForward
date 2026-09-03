<?php
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=AQ.Ab8RN6I5Dk8YsQKw9pkLQsJJwOh8hkM1n5dlOUwZ5mUoEWGgGg";
$data = [
    "systemInstruction" => [
        "parts" => [["text" => "Eres Marquitos"]]
    ],
    "contents" => [
        [
            "role" => "user",
            "parts" => [["text" => "hola"]]
        ],
        [
            "role" => "model",
            "parts" => [["text" => "¡Hola! ¿En qué te puedo ayudar hoy sobre becas o tu orientación vocacional?"]]
        ],
        [
            "role" => "user",
            "parts" => [["text" => "como estas?"]]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$response = curl_exec($ch);
if(curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch) . "\n";
} else {
    echo "Response: " . $response . "\n";
}
curl_close($ch);
