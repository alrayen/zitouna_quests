<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/help-room-controller.php';


$userId = $_SESSION['user_id'] ?? 1; 

// Handle both JSON and FormData inputs
$action = $_POST['action'] ?? '';
$input = [];
if (empty($action)) {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
} else {
    $input = $_POST;
}

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
        } elseif ($existing['status'] === 'not started') {
            $stmt = $pdo->prepare("UPDATE user_challenge_progress SET status = 'started', started_at = NOW() WHERE user_id = ? AND challenge_id = ?");
            $stmt->execute([$userId, $challengeId]);
            echo json_encode(['status' => 'updated_to_started', 'message' => 'Challenge status updated.']);
        } else {
            echo json_encode(['status' => 'already_started', 'current_status' => $existing['status']]);
        }
    }

    
    elseif ($action === 'complete_challenge') {
        $challengeId = (int)$input['challenge_id'];
        
        $proofPath = null;
        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Projet2/uploads/proofs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
            $fileName = 'proof_' . $userId . '_' . $challengeId . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['proof']['tmp_name'], $targetPath)) {
                $proofPath = 'uploads/proofs/' . $fileName;
            } else {
                throw new Exception("Failed to save proof file to " . $uploadDir);
            }
        } else {
            throw new Exception("Proof file is required to complete this challenge.");
        }

        $ptStmt = $pdo->prepare("SELECT points FROM challenge WHERE id_defi = ?");
        $ptStmt->execute([$challengeId]);
        $chalData = $ptStmt->fetch(PDO::FETCH_ASSOC);
        $points = (int)($chalData['points'] ?? 0);

        markChallengeComplete($userId, $challengeId, $pdo, $proofPath);

        $response = addXpAndLevelUp($userId, $points, $pdo);
        
        echo json_encode(array_merge(['status' => 'success'], $response));
    }

    
    elseif ($action === 'complete_challenge_coop') {
        $challengeId = (int)$input['challenge_id'];
        $roomCode = $input['room_code'];
        
        $proofPath = null;
        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Projet2/uploads/proofs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
            $fileName = 'proof_coop_' . $userId . '_' . $challengeId . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['proof']['tmp_name'], $targetPath)) {
                $proofPath = 'uploads/proofs/' . $fileName;
            }
        }

        $ptStmt = $pdo->prepare("SELECT points FROM challenge WHERE id_defi = ?");
        $ptStmt->execute([$challengeId]);
        $chalData = $ptStmt->fetch(PDO::FETCH_ASSOC);
        $basePoints = (int)($chalData['points'] ?? 0);

        $bonusPoints = floor($basePoints * 1.25);

        markChallengeComplete($userId, $challengeId, $pdo, $proofPath);

        $response = addXpAndLevelUp($userId, $bonusPoints, $pdo);

        if (!empty($roomCode)) {
            $helpController = new HelpRoomController();
            $helpController->closeRoom($roomCode);
        }

        echo json_encode(array_merge(['status' => 'success', 'points_awarded' => $bonusPoints], $response));
    }

    
    elseif ($action === 'get_user_stats') {
        $stmt = $pdo->prepare("SELECT xp, level FROM user WHERE id_user = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || is_null($user['xp'])) {
             $pdo->prepare("UPDATE user SET xp = 0 WHERE id_user = ?")->execute([$userId]);
             $user['xp'] = 0;
             $user['level'] = 1;
        }

        echo json_encode([
            'xp' => (int)$user['xp'],
            'level' => (int)$user['level']
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}



function markChallengeComplete($userId, $challengeId, $pdo, $proofPath = null) {
    $check = $pdo->prepare("SELECT id FROM user_challenge_progress WHERE user_id = ? AND challenge_id = ?");
    $check->execute([$userId, $challengeId]);
    
    if ($check->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE user_challenge_progress SET status = 'completed', completed_at = NOW(), proof_file = ? WHERE user_id = ? AND challenge_id = ?");
        $update->execute([$proofPath, $userId, $challengeId]);
    } else {
        $insert = $pdo->prepare("INSERT INTO user_challenge_progress (user_id, challenge_id, status, started_at, completed_at, proof_file) VALUES (?, ?, 'completed', NOW(), NOW(), ?)");
        $insert->execute([$userId, $challengeId, $proofPath]);
    }
}

function addXpAndLevelUp($userId, $points, $pdo) {
    $stmt = $pdo->prepare("SELECT xp, level FROM user WHERE id_user = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $oldXp = (int)($user['xp'] ?? 0);
    $oldLevel = (int)($user['level'] ?? 1);
    $newXp = $oldXp + $points;
    
    $newLevel = floor($newXp / 100) + 1;

    $update = $pdo->prepare("UPDATE user SET xp = ?, level = ? WHERE id_user = ?");
    $update->execute([$newXp, $newLevel, $userId]);

    $response = [
        'new_xp' => $newXp,
        'level_up' => false,
        'new_level' => $newLevel,
        'new_badges' => []
    ];

   
    $response['new_badges'] = checkAndAwardBadges($userId, $newXp, $newLevel, $pdo);

    if ($newLevel > $oldLevel) {
        $response['level_up'] = true;
    }

    return $response;
}

function checkAndAwardBadges($userId, $currentXp, $currentLevel, $pdo) {
    $newBadges = [];

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM user_challenge_progress WHERE user_id = ? AND status = 'completed'");
    $countStmt->execute([$userId]);
    $totalCompleted = $countStmt->fetchColumn();

    $badgesStmt = $pdo->prepare("SELECT * FROM badges WHERE id NOT IN (SELECT badge_id FROM user_badges WHERE user_id = ?)");
    $badgesStmt->execute([$userId]);
    $availableBadges = $badgesStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($availableBadges as $badge) {
        $earned = false;
        
        if ($badge['criteria_type'] === 'challenges_completed' && $totalCompleted >= $badge['criteria_value']) {
            $earned = true;
        } 
        elseif ($badge['criteria_type'] === 'xp_earned' && $currentXp >= $badge['criteria_value']) {
            $earned = true;
        }
        elseif ($badge['criteria_type'] === 'level' && $currentLevel >= $badge['criteria_value']) {
            $earned = true;
        }
        elseif ($badge['criteria_type'] === 'level_reached' && $currentLevel >= $badge['criteria_value']) {
            $earned = true;
        }

        if ($earned) {
            $awardStmt = $pdo->prepare("INSERT INTO user_badges (user_id, badge_id, earned_at) VALUES (?, ?, NOW())");
            $awardStmt->execute([$userId, $badge['id']]);
            $newBadges[] = $badge;
        }
    }
    
    return $newBadges;
}
?>