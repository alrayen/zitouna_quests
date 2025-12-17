<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/challenge.php';

class AiChallengeController {
    
    // ⚠️ SECURITY WARNING: You posted your real key. I have removed it.
    // Please generate a new one at OpenRouter.ai and paste it here.
    private $apiKey = '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'; 

    public function generateChallengeForUser($userId) {
        // FIX #1: Allow this specific script to run for 5 minutes (300 seconds)
        // This prevents the "Maximum execution time of 30 seconds exceeded" error
        set_time_limit(300); 

        $pdo = config::getConnexion();
        
        // 1. Get User Stats
        $sql = "SELECT c.difficulty, 
                        AVG(TIMESTAMPDIFF(MINUTE, ucp.started_at, ucp.completed_at)) as avg_time
                FROM user_challenge_progress ucp
                JOIN challenge c ON ucp.challenge_id = c.id_defi
                WHERE ucp.user_id = :uid AND ucp.status = 'completed'
                GROUP BY c.difficulty";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Context
        if (empty($stats)) {
            $userContext = "The user is a beginner with no completed challenges yet.";
        } else {
            $userContext = "User Stats: " . json_encode($stats);
        }

        // 3. Prompt
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

        // 4. Call API with Fallback
        try {
            $generatedData = $this->callOpenRouterWithFallback($prompt);
        } catch (Exception $e) {
            // FIX #2: Don't just return false. Show the actual error to debug!
            // Once fixed, you can change this back to return false;
            die("<strong>CRITICAL ERROR:</strong> " . $e->getMessage());
        }
        
        // 5. Clean Description
        $cleanDesc = $generatedData['description'];
        $cleanDesc = str_replace('</p>', '<br><br>', $cleanDesc);
        $cleanDesc = str_replace('<p>', '', $cleanDesc);
        $cleanDesc = trim($cleanDesc);

        // 6. Save
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
                // Keep track of the error but continue to the next model
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
        
        // FIX #3: Ensure cURL timeout matches the PHP script extension
        curl_setopt($ch, CURLOPT_TIMEOUT, 180); 

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('Connection Error: ' . $error);
        }
        curl_close($ch);

        $decoded = json_decode($response, true);

        // Check for API-level errors (like 401 Unauthorized or 402 Insufficient Credits)
        if (isset($decoded['error'])) {
             throw new Exception("API Error ($model): " . ($decoded['error']['message'] ?? 'Unknown'));
        }

        if (!isset($decoded['choices'][0]['message']['content'])) {
            throw new Exception("Empty response from ($model)");
        }

        $content = $decoded['choices'][0]['message']['content'];

        // Clean DeepSeek <think> tags
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