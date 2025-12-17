<?php
session_start();
require_once 'AiChallengeController.php';

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
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