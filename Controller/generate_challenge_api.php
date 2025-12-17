<?php
session_start();
require_once 'AiChallengeController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

$userId = $_SESSION['user_id'];
$aiController = new AiChallengeController();

try {
    $newChallengeId = $aiController->generateChallengeForUser($userId);
    
    if ($newChallengeId) {
        echo json_encode([
            'status' => 'success', 
            'challenge_id' => $newChallengeId,
            'message' => 'Challenge generated successfully!'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'AI could not generate a challenge right now.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>