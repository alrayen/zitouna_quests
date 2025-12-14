<?php

        include_once __DIR__ . '/../config.php';

        function modererCommentaire($text)
        {
            $apiKey= GEMINI_API_KEY;
            $url ="https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=". $apiKey;
            $prompt="Analyse ce texte et détermine s'il contient des propos grossiers, sexuels, inappropriés, offensants, haineux ou du harcèlement. Réponds UNIQUEMENT par 'INAPPROPRIE' si le texte est problématique, ou 'APPROPRIE' s'il est acceptable. Texte à analyser : \"" . $text . "\"";
            $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.1,
            "maxOutputTokens" => 50
        ]
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("Erreur API Gemini : " . $response);
        
         //return 0; // En cas d'erreur, on accepte par défaut
        
    }
    
    $result = json_decode($response, true);
    
    if(isset($result['candidates'][0]['content']['parts'][0]['text']))
    {
        $reponse=strtoupper($result['candidates'][0]['content']['parts'][0]['text']);
         
        if(str_contains($reponse,'IN')===true)
        {
            return -1;
            
        }
          return 1;

    }
        }








?>