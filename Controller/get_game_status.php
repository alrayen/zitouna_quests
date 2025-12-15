<?php
// Controller/get_game_status.php

// 1. CLEANING MODE ON
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
$response = ['success' => false];

try {
    if ($session_id === 0) throw new Exception("No ID");

    $pdo = config::getConnexion();

    // 1. Get Session Info
    $stmt = $pdo->prepare("SELECT game_state, current_question_index, host_user_id FROM online_sessions WHERE session_id = ?");
    $stmt->execute([$session_id]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$session) throw new Exception("Session not found");

    // 2. Get Players & Scores
    $playerStmt = $pdo->prepare("SELECT user.id_user as id, user.nom as username, sp.score_total, sp.is_host 
                                 FROM session_players sp
                                 JOIN user ON sp.user_id = user.id_user
                                 WHERE sp.session_id = ?");
    $playerStmt->execute([$session_id]);
    $players = $playerStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // UTF-8 Encode usernames just in case
    foreach($players as &$p) {
        $p['username'] = utf8_encode($p['username']);
    }

    // 3. Check Answers
    $totalPlayers = count($players);
    if($totalPlayers == 0) $totalPlayers = 1; // Prevent division by zero

    $ansStmt = $pdo->prepare("SELECT COUNT(*) FROM session_answers WHERE session_id = ? AND question_index = ?");
    $ansStmt->execute([$session_id, $session['current_question_index']]);
    $answeredCount = $ansStmt->fetchColumn();

    $all_answered = ($answeredCount >= $totalPlayers);

    $response = [
        'success' => true,
        'state' => $session['game_state'], 
        'current_question_index' => (int)$session['current_question_index'],
        'host_id' => (int)$session['host_user_id'],
        'players' => $players,
        'players_answered' => (int)$answeredCount,
        'players_total' => (int)$totalPlayers,
        'all_answered' => $all_answered
    ];

} catch (Exception $e) {
    $response = ['success' => false, 'error' => $e->getMessage()];
}

// 2. CLEAN AND SEND
ob_end_clean();
echo json_encode($response);
?>