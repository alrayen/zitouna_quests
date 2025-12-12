<?php
// Controller/gamification_api.php
session_start();
header('Content-Type: application/json');

// --- 1. CONFIGURATION ---
require_once __DIR__ . '/../config.php';
// Include the Help Room Controller to close rooms upon completion
require_once __DIR__ . '/help-room-controller.php';

// --- 2. AUTH CHECK ---
// Default to 1 for testing if session is missing
$userId = $_SESSION['user_id'] ?? 1; 

// --- 3. INPUT HANDLING ---
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

try {
    $pdo = config::getConnexion();

    // =================================================================
    // ACTION: START CHALLENGE
    // =================================================================
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

    // =================================================================
    // ACTION: COMPLETE CHALLENGE (Standard)
    // =================================================================
    elseif ($action === 'complete_challenge') {
        $challengeId = (int)$input['challenge_id'];
        
        // 1. Fetch Points
        $ptStmt = $pdo->prepare("SELECT points FROM challenge WHERE id_defi = ?");
        $ptStmt->execute([$challengeId]);
        $chalData = $ptStmt->fetch(PDO::FETCH_ASSOC);
        $points = (int)($chalData['points'] ?? 0);

        // 2. Mark Progress
        markChallengeComplete($userId, $challengeId, $pdo);

        // 3. Award XP, Check Levels & Badges
        $response = addXpAndLevelUp($userId, $points, $pdo);
        
        echo json_encode(array_merge(['status' => 'success'], $response));
    }

    // =================================================================
    // ACTION: COMPLETE CHALLENGE CO-OP (Bonus 25%)
    // =================================================================
    elseif ($action === 'complete_challenge_coop') {
        $challengeId = (int)$input['challenge_id'];
        $roomCode = $input['room_code'];
        
        // 1. Fetch Base Points
        $ptStmt = $pdo->prepare("SELECT points FROM challenge WHERE id_defi = ?");
        $ptStmt->execute([$challengeId]);
        $chalData = $ptStmt->fetch(PDO::FETCH_ASSOC);
        $basePoints = (int)($chalData['points'] ?? 0);

        // 2. Apply Bonus
        $bonusPoints = floor($basePoints * 1.25);

        // 3. Mark Progress
        markChallengeComplete($userId, $challengeId, $pdo);

        // 4. Award XP (With Bonus)
        $response = addXpAndLevelUp($userId, $bonusPoints, $pdo);

        // 5. Close the Help Room
        if (!empty($roomCode)) {
            $helpController = new HelpRoomController();
            $helpController->closeRoom($roomCode);
        }

        echo json_encode(array_merge(['status' => 'success', 'points_awarded' => $bonusPoints], $response));
    }

    // =================================================================
    // ACTION: GET USER STATS (Auto-Refresh)
    // =================================================================
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

// =================================================================
// HELPER FUNCTIONS (Keep logic clean and reusable)
// =================================================================

function markChallengeComplete($userId, $challengeId, $pdo) {
    $check = $pdo->prepare("SELECT id FROM user_challenge_progress WHERE user_id = ? AND challenge_id = ?");
    $check->execute([$userId, $challengeId]);
    
    if ($check->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE user_challenge_progress SET status = 'completed', completed_at = NOW() WHERE user_id = ? AND challenge_id = ?");
        $update->execute([$userId, $challengeId]);
    } else {
        $insert = $pdo->prepare("INSERT INTO user_challenge_progress (user_id, challenge_id, status, started_at, completed_at) VALUES (?, ?, 'completed', NOW(), NOW())");
        $insert->execute([$userId, $challengeId]);
    }
}

function addXpAndLevelUp($userId, $points, $pdo) {
    // 1. Get Current Stats
    $stmt = $pdo->prepare("SELECT xp, level FROM user WHERE id_user = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $oldXp = (int)($user['xp'] ?? 0);
    $oldLevel = (int)($user['level'] ?? 1);
    $newXp = $oldXp + $points;
    
    // 2. Calculate New Level (1 Level per 100 XP)
    $newLevel = floor($newXp / 100) + 1;

    // 3. Update User
    $update = $pdo->prepare("UPDATE user SET xp = ?, level = ? WHERE id_user = ?");
    $update->execute([$newXp, $newLevel, $userId]);

    $response = [
        'new_xp' => $newXp,
        'level_up' => false,
        'new_level' => $newLevel,
        'new_badges' => []
    ];

    // 4. Level Up Event & Badges
    // We check badges even if no level up, just in case (e.g. XP badges)
    $response['new_badges'] = checkAndAwardBadges($userId, $newXp, $newLevel, $pdo);

    if ($newLevel > $oldLevel) {
        $response['level_up'] = true;
    }

    return $response;
}

function checkAndAwardBadges($userId, $currentXp, $currentLevel, $pdo) {
    $newBadges = [];

    // Count total completed challenges
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM user_challenge_progress WHERE user_id = ? AND status = 'completed'");
    $countStmt->execute([$userId]);
    $totalCompleted = $countStmt->fetchColumn();

    // Fetch unearned badges
    $badgesStmt = $pdo->prepare("SELECT * FROM badges WHERE id NOT IN (SELECT badge_id FROM user_badges WHERE user_id = ?)");
    $badgesStmt->execute([$userId]);
    $availableBadges = $badgesStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($availableBadges as $badge) {
        $earned = false;
        
        // Check: Challenges Count
        if ($badge['criteria_type'] === 'challenges_completed' && $totalCompleted >= $badge['criteria_value']) {
            $earned = true;
        } 
        // Check: XP Threshold
        elseif ($badge['criteria_type'] === 'xp_earned' && $currentXp >= $badge['criteria_value']) {
            $earned = true;
        }
        // Check: Level Reached (Matches your Level 30 requirement)
        elseif ($badge['criteria_type'] === 'level' && $currentLevel >= $badge['criteria_value']) {
            $earned = true;
        }
        // Legacy support if you used 'level_reached' in DB
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