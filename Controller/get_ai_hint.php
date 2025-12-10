<?php
// Controller/get_ai_hint.php

// 1. PREVENT HTML ERROR OUTPUT
ini_set('display_errors', 0); 
error_reporting(0);
header('Content-Type: application/json');

// 2. CATCH FATAL ERRORS
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        echo json_encode(['success' => false, 'message' => 'Fatal PHP Error: ' . $error['message']]);
        die();
    }
});

session_start();

try {
    // 3. CHECK PATHS
    $configPath = __DIR__ . '/../config.php';
    if (!file_exists($configPath)) throw new Exception("Config not found.");
    require_once $configPath;

    $ctrlPath = __DIR__ . '/question-controller.php';
    if (!file_exists($ctrlPath)) throw new Exception("Controller not found.");
    require_once $ctrlPath;

    // 4. GET DATA
    $session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
    $question_index = isset($_POST['question_index']) ? (int)$_POST['question_index'] : 0;

    if ($session_id === 0) throw new Exception("Session ID is 0.");

    // 5. DB & LOGIC
    $pdo = config::getConnexion();

    $stmt = $pdo->prepare("SELECT current_quiz_id FROM online_sessions WHERE session_id = ?");
    $stmt->execute([$session_id]);
    $quiz_id = $stmt->fetchColumn();

    if (!$quiz_id) throw new Exception("Session not found in DB.");

    $qController = new QuestionController();
    $questions = $qController->listQuestionsByQuizId($quiz_id);
    $array_index = $question_index - 1;

    if (!isset($questions[$array_index])) throw new Exception("Question index not found.");

    $q = $questions[$array_index];

    // --- FIX IS HERE: USE THE CORRECT METHOD FROM YOUR CONTROLLER ---
    // Your controller uses getTextQuestion(), so we use it here too.
    if (method_exists($q, 'getTextQuestion')) {
        $question_text = $q->getTextQuestion();
    } else {
        // Fallback just in case
        $question_text = "Question text missing";
    }
    // ---------------------------------------------------------------

    $correct_answer = $q->getBonneReponse();

    // 6. API CALL (OpenRouter/DeepSeek)
    $apiKey = config::getGeminiKey(); 
    $apiUrl = "https://openrouter.ai/api/v1/chat/completions";

    $data = [
        "model" => "deepseek/deepseek-chat",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful tutor. Give a short, cryptic hint (max 15 words). Do not give the answer."],
            ["role" => "user", "content" => "Question: $question_text\nAnswer: $correct_answer\nHint:"]
        ]
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'HTTP-Referer: http://localhost',
        'X-Title: Zitouna Quest'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    // SSL Fix for free hosting
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    
    $api_result = curl_exec($ch);
    
    if (curl_errno($ch)) throw new Exception('cURL Error: ' . curl_error($ch));
    curl_close($ch);

    $json = json_decode($api_result, true);

    // 7. PARSE RESPONSE
    if (isset($json['error'])) {
        $hint = "Hint: Focus on the keywords.";
    } else {
        $hint = $json['choices'][0]['message']['content'] ?? "Think carefully!";
    }

    echo json_encode(['success' => true, 'hint' => $hint]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>