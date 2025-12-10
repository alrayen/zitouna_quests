<?php
// Controller/generate_ai_quiz.php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';

try {
    $topic = $_POST['topic'] ?? '';
    $level = $_POST['level'] ?? 'Medium';
    $count = (int)($_POST['count'] ?? 5);
    $points = (int)($_POST['points'] ?? 10); // Receive points

    if (empty($topic)) throw new Exception("Topic is required.");

    $apiKey = config::getGeminiKey(); 
    $apiUrl = "https://openrouter.ai/api/v1/chat/completions";

    $prompt = "Create a quiz about '$topic'. Difficulty: $level. 
    Generate exactly $count multiple-choice questions. 
    Return ONLY raw JSON with this structure: 
    [
      {
        \"question\": \"Question text\",
        \"options\": [\"Option1\", \"Option2\", \"Option3\", \"Option4\"],
        \"answer\": \"Correct Option Text\"
      }
    ]";

    $data = [
        "model" => "deepseek/deepseek-chat",
        "messages" => [
            ["role" => "system", "content" => "You are a quiz generator that outputs strict JSON."],
            ["role" => "user", "content" => $prompt]
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    if (curl_errno($ch)) throw new Exception(curl_error($ch));
    curl_close($ch);

    $jsonResponse = json_decode($response, true);
    $rawContent = $jsonResponse['choices'][0]['message']['content'] ?? '';

    $rawContent = str_replace(["```json", "```"], "", $rawContent);
    $quizData = json_decode($rawContent, true);

    if (!$quizData) throw new Exception("AI generation failed or invalid format.");

    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("INSERT INTO quiz (titre, categorie, niveau) VALUES (?, 'Generated', ?)");
    $stmt->execute(["AI: $topic", $level]);
    $quizId = $pdo->lastInsertId();
    
    // Note: We are using 'points' just for logic here, ideally you'd save it to the DB if you had a column
    $qStmt = $pdo->prepare("INSERT INTO question (id_quiz, text, option1, option2, option3, option4, bonne) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($quizData as $q) {
        $qStmt->execute([
            $quizId,
            $q['question'],
            $q['options'][0],
            $q['options'][1],
            $q['options'][2],
            $q['options'][3],
            $q['answer']
        ]);
    }

    echo json_encode(['success' => true, 'quiz_id' => $quizId]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>