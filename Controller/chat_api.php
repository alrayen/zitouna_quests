<?php
// chat_api.php (Located in PROJET/Controller/)

session_start();
session_write_close(); // Prevent site freezing

header('Content-Type: application/json');

// --- 1. INCLUDES ---
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/challenge.php';
require_once __DIR__ . '/../Model/ressources-model.php';
require_once __DIR__ . '/challenge-controller.php';
require_once __DIR__ . '/ressources-controller.php';

// --- 2. INPUT HANDLING ---
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$challengeId = $input['challenge_id'] ?? 0;

if (trim($userMessage) === '') {
    echo json_encode(['reply' => '']);
    exit;
}

// --- 3. FETCH DATA & DETERMINE PERSONA ---
$challengeCtrl = new ChallengeController();
$challenge = $challengeCtrl->getChallengeById($challengeId);

// Define Tone based on Difficulty
$difficulty = $challenge ? $challenge->getDifficulty() : 'General';
$toneInstruction = "";

switch (strtolower($difficulty)) {
    case 'easy':
        $toneInstruction = "TONE: Be energetic, super encouraging, and use emojis! 🌟 Act like a friendly cheerleader.";
        break;
    case 'medium':
        $toneInstruction = "TONE: Be professional, clear, and helpful. Act like a supportive colleague.";
        break;
    case 'hard':
        $toneInstruction = "TONE: Be concise, technical, and direct. Act like a senior engineer. No fluff.";
        break;
    case 'expert':
        $toneInstruction = "TONE: Be cryptic but wise. Act like a grandmaster or Yoda. Challenge the user to think.";
        break;
    default:
        $toneInstruction = "TONE: Be a helpful assistant.";
}

// --- 4. SMART LOGIC (Greeting vs Context) ---
$cleanText = trim(strtolower($userMessage));
$greetingWords = ['hi', 'hello', 'hey', 'bonjour', 'salut', 'yo', 'greetings'];
$isGreeting = false;

if (strlen($cleanText) < 20) {
    foreach ($greetingWords as $word) {
        if (strpos($cleanText, $word) === 0) {
            $isGreeting = true;
            break;
        }
    }
}

if ($isGreeting) {
    // Greeting Mode: No context, just personality
    $systemPrompt = "You are an AI mentor. $toneInstruction The user just said hello. Reply with a short, welcoming greeting matching your tone.";
} else {
    // Context Mode: Full Brain Power
    $resourceCtrl = new RessourceController();
    $resources = $resourceCtrl->getResourcesByDefiId($challengeId);
    
    $systemPrompt = "You are an expert technical mentor. $toneInstruction\n";
    
    if ($challenge) {
        $systemPrompt .= "--- CONTEXT DATA ---\n";
        $systemPrompt .= "Task: {$challenge->getTitre()}\n";
        $systemPrompt .= "Description: {$challenge->getDescription()}\n";
        if (!empty($resources)) {
            $systemPrompt .= "Files: ";
            foreach ($resources as $r) { $systemPrompt .= $r->getNom() . ", "; }
            $systemPrompt .= "\n";
        }
        $systemPrompt .= "--------------------\n";
        $systemPrompt .= "INSTRUCTION: Use the Context Data to answer. Be brief.";
    }
}

// --- 5. CALL OLLAMA ---
$apiUrl = 'http://127.0.0.1:11434/v1/chat/completions';

$payload = [
    'model' => 'deepseek-r1:1.5b', 
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.6
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 45); // Increased timeout slightly for reasoning

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['reply' => 'Error: Is Ollama running?']);
} else {
    $decoded = json_decode($response, true);
    if (isset($decoded['choices'][0]['message']['content'])) {
        $rawReply = $decoded['choices'][0]['message']['content'];
        
        // --- REASONING EXTRACTION ---
        $thoughtProcess = '';
        // Extract content inside <think> tags
        if (preg_match('/<think>(.*?)<\/think>/s', $rawReply, $matches)) {
            $thoughtProcess = $matches[1];
            // Remove the thought block from the main reply
            $rawReply = preg_replace('/<think>.*?<\/think>/s', '', $rawReply);
        }
        
        // Send back Reply AND Thought separately
        echo json_encode([
            'reply' => trim($rawReply), 
            'thought' => trim($thoughtProcess)
        ]);
    } else {
        echo json_encode(['reply' => 'AI Error']);
    }
}
curl_close($ch);
?>