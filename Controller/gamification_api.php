<?php
// gamification_api.php (Located in PROJET/Controller/)

session_start();
header('Content-Type: application/json');

// --- 1. CONFIGURATION & DATABASE CONNECTION ---
// Adjust path to config based on your folder structure
require_once __DIR__ . '/../config.php';


$userId = $_SESSION['user_id'] ?? 1; 

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

try {
    $pdo = config::getConnexion();


    if ($action === 'start_challenge') {
        $challengeId = (int)$input['challenge_id'];
        
        $checkStmt = $pdo->prepare("SELECT status FROM user_challenge_progress WHERE user_id = ? AND challenge_id = ?");
        $checkStmt->execute([$userId, $challengeId]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO user_challenge_progress (user_id, challenge_id, status, started_at) VALUES (?, ?, 'started', NOW())");
            $stmt->execute([$userId, $challengeId]);
            echo json_encode(['status' => 'started_new', 'message' => 'Challenge started!']);
        } else {
            echo json_encode(['status' => 'already_started', 'current_status' => $existing['status']]);
        }
    }


    elseif ($action === 'complete_challenge') {
        $challengeId = (int)$input['challenge_id'];
        $points = (int)$input['points'];

        $checkStmt = $pdo->prepare("SELECT status FROM user_challenge_progress WHERE user_id = ? AND challenge_id = ?");
        $checkStmt->execute([$userId, $challengeId]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing && $existing['status'] === 'completed') {
            echo json_encode(['status' => 'already_completed', 'message' => 'Mission already completed!']);
            exit;
        }

        if ($existing) {
            $updateStmt = $pdo->prepare("UPDATE user_challenge_progress SET status = 'completed', completed_at = NOW() WHERE user_id = ? AND challenge_id = ?");
            $updateStmt->execute([$userId, $challengeId]);
        } else {
            $insertStmt = $pdo->prepare("INSERT INTO user_challenge_progress (user_id, challenge_id, status, started_at, completed_at) VALUES (?, ?, 'completed', NOW(), NOW())");
            $insertStmt->execute([$userId, $challengeId]);
        }

        $updateUser = $pdo->prepare("UPDATE users SET xp = xp + ? WHERE id = ?");
        $updateUser->execute([$points, $userId]);

        
        $userStmt = $pdo->prepare("SELECT xp, level FROM users WHERE id = ?");
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        $currentXp = $user['xp'];
        $currentLevel = $user['level'];

        $calculatedLevel = floor($currentXp / 100) + 1;
        $levelUp = false;

        if ($calculatedLevel > $currentLevel) {
            $pdo->prepare("UPDATE users SET level = ? WHERE id = ?")->execute([$calculatedLevel, $userId]);
            $levelUp = true;
        }

        echo json_encode([
            'status' => 'success',
            'earned_xp' => $points,
            'total_xp' => $currentXp,
            'level_up' => $levelUp,
            'new_level' => $calculatedLevel
        ]);
    }

    
    elseif ($action === 'get_user_stats') {
        $stmt = $pdo->prepare("SELECT xp, level FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'xp' => $user['xp'] ?? 0,
            'level' => $user['level'] ?? 1
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>