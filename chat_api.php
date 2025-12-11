<?php

session_start();
session_write_close(); 

header('Content-Type: application/json');


require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/challenge.php';
require_once __DIR__ . '/../Model/ressources-model.php';
require_once __DIR__ . '/challenge-controller.php';
require_once __DIR__ . '/ressources-controller.php';

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$challengeId = $input['challenge_id'] ?? 0;

if (trim($userMessage) === '') {
    echo json_encode(['reply' => '']);
    exit;
}

$challengeCtrl = new ChallengeController();
$challenge = $challengeCtrl->getChallengeById($challengeId);

$difficulty = $challenge ? strtolower($challenge->getDifficulty()) : 'general';
$toneInstruction = "TONE: Be helpful."; 

switch ($difficulty) {
    case 'easy':
        $toneInstruction = "TONE: Be super energetic, friendly, and use emojis! 🌟 Act like a supportive cheerleader.";
        break;
    case 'medium':
        $toneInstruction = "TONE: Be professional, clear, and logical. Act like a helpful colleague.";
        break;
    case 'hard':
        $toneInstruction = "TONE: Be direct, technical, and concise. Act like a senior engineer. No fluff.";
        break;
    case 'expert':
        $toneInstruction = "TONE: Be wise and cryptic. Act like a master sensei. Challenge the user to think.";
        break;
}

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
    
    $systemPrompt = "You are an AI mentor. $toneInstruction The user just said hello. Reply with a short, welcoming greeting matching your tone.";
} else {
    
    $resourceCtrl = new RessourceController();
    $resources = $resourceCtrl->getResourcesByDefiId($challengeId);
    
    $systemPrompt = "You are an expert mentor. $toneInstruction\n";
    
    if ($challenge) {
        $systemPrompt .= "--- CONTEXT ---\n";
        $systemPrompt .= "Task: {$challenge->getTitre()}\n";
        $systemPrompt .= "Details: {$challenge->getDescription()}\n";
        if (!empty($resources)) {
            $systemPrompt .= "Files: ";
            foreach ($resources as $r) { $systemPrompt .= $r->getNom() . ", "; }
            $systemPrompt .= "\n";
        }
        $systemPrompt .= "---------------\n";
        $systemPrompt .= "INSTRUCTION: Use the Context to help the user. Be brief.";
    }
}

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
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ollama'
]);


curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 45); 
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['reply' => 'Error: Is Ollama running? (' . curl_error($ch) . ')']);
} else {
    $decoded = json_decode($response, true);
    
    if (isset($decoded['choices'][0]['message']['content'])) {
        $rawReply = $decoded['choices'][0]['message']['content'];
        
        $thoughtProcess = '';
        
        
        if (preg_match('/<think>(.*?)<\/think>/s', $rawReply, $matches)) {
            $thoughtProcess = $matches[1]; 
            $rawReply = preg_replace('/<think>.*?<\/think>/s', '', $rawReply); // Remove thought from reply
        }
        
        echo json_encode([
            'reply' => trim($rawReply),
            'thought' => trim($thoughtProcess)
        ]);
        
    } else {
        $errMsg = $decoded['error']['message'] ?? 'Unknown error';
        echo json_encode(['reply' => 'AI Error: ' . $errMsg]);
    }
}
curl_close($ch);
?>