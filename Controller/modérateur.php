<?php

        include_once __DIR__ . '/../config.php';

        function modererCommentaire($text)
        {
            // --- ÉTAPE 0 : FILTRE LOCAL DE SÉCURITÉ (SÉCURITÉ SUPPLÉMENTAIRE) ---
            $badWords = ['fuck', 'shit', 'connard', 'merde', 'putain', 'salope', 'asshole', 'bitch'];
            foreach ($badWords as $word) {
                if (stripos($text, $word) !== false) {
                    error_log("Modération locale : Mot interdit détecté ('$word')");
                    return -1;
                }
            }

            // --- ÉTAPE 1 : RÉCUPÉRATION DE LA CLÉ ---
            $apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';
            
            if (empty($apiKey) || strpos($apiKey, 'AIza') === false) {
                if (class_exists('config')) {
                    $apiKey = config::getGeminiKey();
                }
            }

            if (empty($apiKey) || $apiKey === 'YOUR_GEMINI_API_KEY') {
                error_log("Erreur Modérateur : Aucune clé API valide trouvée.");
                return 1; // On laisse passer si la config est cassée pour ne pas bloquer le site
            }

            // --- ÉTAPE 2 : PRÉPARATION DE L'APPEL ---
            $prompt = "Tu es un modérateur. Analyse ce texte : \"" . $text . "\". Est-il inapproprié (insultes, haine, sexe, vulgarité) ? Réponds UNIQUEMENT par 'INAPPROPRIE' ou 'APPROPRIE'.";

            $isOpenRouter = (strpos($apiKey, 'sk-or-') === 0);
            
            if ($isOpenRouter) {
                $url = "https://openrouter.ai/api/v1/chat/completions";
                $data = [
                    "model" => "google/gemini-flash-1.5",
                    "messages" => [["role" => "user", "content" => $prompt]],
                    "temperature" => 0.0
                ];
                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey,
                    'HTTP-Referer: http://localhost',
                    'X-Title: Zitouna Quests'
                ];
            } else {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;
                $data = [
                    "contents" => [["parts" => [["text" => $prompt]]]],
                    "generationConfig" => ["temperature" => 0.0, "maxOutputTokens" => 10],
                    "safetySettings" => [
                        ["category" => "HARM_CATEGORY_HARASSMENT", "threshold" => "BLOCK_NONE"],
                        ["category" => "HARM_CATEGORY_HATE_SPEECH", "threshold" => "BLOCK_NONE"],
                        ["category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT", "threshold" => "BLOCK_NONE"],
                        ["category" => "HARM_CATEGORY_DANGEROUS_CONTENT", "threshold" => "BLOCK_NONE"]
                    ]
                ];
                $headers = ['Content-Type: application/json'];
            }
    
    // --- ÉTAPE 3 : EXÉCUTION CURL ---
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // IMPORTANT : Désactiver la vérification SSL pour XAMPP (résout souvent les erreurs de connexion)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("Erreur API Gemini (Code $httpCode). Erreur Curl : $curlError. Réponse : " . $response);
        return 1; 
    }
    
    // --- ÉTAPE 4 : ANALYSE DE LA RÉPONSE ---
    $result = json_decode($response, true);
    $reponseText = "";

    if ($isOpenRouter) {
        $reponseText = $result['choices'][0]['message']['content'] ?? "";
    } else {
        if (isset($result['candidates'][0]['finishReason']) && $result['candidates'][0]['finishReason'] === 'SAFETY') {
            return -1;
        }
        $reponseText = $result['candidates'][0]['content']['parts'][0]['text'] ?? "";
    }

    if (!empty($reponseText)) {
        $reponse = strtoupper(trim($reponseText));
        if (strpos($reponse, 'IN') !== false || strpos($reponse, 'NON') !== false || strpos($reponse, 'BAD') !== false) {
            return -1;
        }
    }
    
    return 1;
}

?>