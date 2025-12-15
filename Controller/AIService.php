<?php
require_once __DIR__ . '/../config.php'; 

if (true) { 
    class AIService {
        private $apiKey;
        private $apiEndpoint = "https://openrouter.ai/api/v1/chat/completions"; 
        public function __construct() {
            $this->apiKey = config::getGeminiKey(); 
        }

        public function generateQuiz(string $topic, string $level, int $count): array {
            $systemInstruction = "You are a helpful assistant that outputs ONLY valid JSON. No markdown, no conversational text.";
            
            $userPrompt = "Create a quiz about '{$topic}' (Level: {$level}). Exactly {$count} questions. 
            Structure the response as a single JSON object:
            { \"questions\": [ { \"text\": \"Question text here\", \"option1\": \"A\", \"option2\": \"B\", \"option3\": \"C\", \"option4\": \"D\", \"bonne\": 1 } ] }
            Ensure 'bonne' is an integer (1, 2, 3, or 4).";

            
            $payload = json_encode([
                "model" => "deepseek/deepseek-chat-v3.1",
                "messages" => [
                    ["role" => "system", "content" => $systemInstruction],
                    ["role" => "user", "content" => $userPrompt]
                ],
                "temperature" => 0.7,
                "max_tokens" => 2000
            ]);
            $ch = curl_init($this->apiEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $this->apiKey,
                "Content-Type: application/json",
                "HTTP-Referer: http://localhost", 
                "X-Title: QuizGenerator"          
            ]);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);

            if ($curl_error) {
                error_log("cURL Error: $curl_error");
                return []; 
            }

            if ($http_code !== 200) {
                echo "<div style='background:orange; padding:10px;'>";
                echo "<h3>OpenRouter API Error $http_code</h3>";
                echo "<strong>Response:</strong> " . htmlspecialchars($response);
                echo "</div>";
                return [];
            }

            $data = json_decode($response, true);
            
            $raw_text = $data['choices'][0]['message']['content'] ?? null;
            
            if (!$raw_text) {
                return [];
            }

            $start = strpos($raw_text, '{');
            $end = strrpos($raw_text, '}');
            
            if ($start !== false && $end !== false) {
                $clean_json = substr($raw_text, $start, $end - $start + 1);
            } else {
                $clean_json = $raw_text;
            }

            $result = json_decode($clean_json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "<h3>JSON Decode Error</h3>";
                echo "Raw Output: " . htmlspecialchars($raw_text);
                return [];
            }

            return $result['questions'] ?? []; 
        }
    }
}
?>