<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/challenge.php';

class AiChallengeController {
    
    
    private $apiKey = ''; 

    public function generateChallengeForUser($userId) {
        
        set_time_limit(300); 

        $pdo = config::getConnexion();
        
        
        $sql = "SELECT c.difficulty, 
                        AVG(TIMESTAMPDIFF(MINUTE, ucp.started_at, ucp.completed_at)) as avg_time
                FROM user_challenge_progress ucp
                JOIN challenge c ON ucp.challenge_id = c.id_defi
                WHERE ucp.user_id = :uid AND ucp.status = 'completed'
                GROUP BY c.difficulty";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        if (empty($stats)) {
            $userContext = "The user is a beginner with no completed challenges yet.";
        } else {
            $userContext = "User Stats: " . json_encode($stats);
        }

        
        $prompt = "
            You are a coding mentor. Create a unique coding challenge based on this context: $userContext.
            
            REQUIREMENTS:
            1. 'description' MUST be detailed (150+ words). 
            2. FORMATTING: Use <ul>, <li>, <strong>, <pre>, and <br> for structure. DO NOT USE <p> TAGS.
            3. Include a Scenario, Task, and Example.
            4. Return ONLY valid JSON.
            
            JSON Structure:
            {
                \"titre\": \"Title\",
                \"description\": \"Content...\",
                \"categorie\": \"Code\",
                \"difficulty\": \"Easy\",
                \"points\": 100,
                \"time\": 45
            }
        ";

        try {
            $generatedData = $this->callOpenRouterWithFallback($prompt);
        } catch (Exception $e) {
            
            die("<strong>CRITICAL ERROR:</strong> " . $e->getMessage());
        }
        
        $cleanDesc = $generatedData['description'];
        $cleanDesc = str_replace('</p>', '<br><br>', $cleanDesc);
        $cleanDesc = str_replace('<p>', '', $cleanDesc);
        $cleanDesc = trim($cleanDesc);

        $sqlInsert = "INSERT INTO challenge (titre, description, categorie, points, time, difficulty, status, place) 
                        VALUES (:titre, :description, :categorie, :points, :time, :difficulty, 'Active', 'AI Lab')";
        
        $insertStmt = $pdo->prepare($sqlInsert);
        $insertStmt->execute([
            'titre' => $generatedData['titre'] . " (AI)",
            'description' => $cleanDesc,
            'categorie' => $generatedData['categorie'] ?? 'Code',
            'points' => $generatedData['points'],
            'time' => $generatedData['time'],
            'difficulty' => $generatedData['difficulty']
        ]);

        return $pdo->lastInsertId();
    }

    private function callOpenRouterWithFallback($prompt) {
        $models = [
            'deepseek/deepseek-r1',          
            'google/gemini-2.0-flash-lite-preview-02-05:free', 
            'meta-llama/llama-3-8b-instruct:free'              
        ];

        $lastError = "";

        foreach ($models as $model) {
            try {
                return $this->callOpenRouterCurl($prompt, $model);
            } catch (Exception $e) {
                $lastError = $e->getMessage();
                continue; 
            }
        }

        throw new Exception("All AI models failed. Last error: " . $lastError);
    }

    private function callOpenRouterCurl($prompt, $model) {
        $apiUrl = trim('https://openrouter.ai/api/v1/chat/completions');
        
        $maxTokens = ($model === 'deepseek/deepseek-r1') ? 3000 : 1000;

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a JSON generator. Output only raw JSON.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.6,
            'max_tokens' => $maxTokens 
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
            'HTTP-Referer: http://localhost/Projet', 
            'X-Title: Zitouna Quests'
        ]);

        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 180); 

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('Connection Error: ' . $error);
        }
        curl_close($ch);

        $decoded = json_decode($response, true);

        if (isset($decoded['error'])) {
             throw new Exception("API Error ($model): " . ($decoded['error']['message'] ?? 'Unknown'));
        }

        if (!isset($decoded['choices'][0]['message']['content'])) {
            throw new Exception("Empty response from ($model)");
        }

        $content = $decoded['choices'][0]['message']['content'];

        if (preg_match('/<think>(.*?)<\/think>/s', $content)) {
            $content = preg_replace('/<think>.*?<\/think>/s', '', $content);
        }

        if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
            $jsonString = $matches[0];
        } else {
            $jsonString = trim(str_replace(['```json', '```'], '', $content));
        }

        $finalJson = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $debugSnippet = substr(strip_tags($content), 0, 200);
            throw new Exception("Invalid JSON. Received: " . $debugSnippet);
        }

        return $finalJson;
    }
}
?>