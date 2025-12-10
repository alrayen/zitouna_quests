<?php
session_start();
require_once __DIR__ . '/../config.php';
$pdo = config::getConnexion();

$session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
$bot_id = 999;

if ($session_id > 0) {
    $check = $pdo->prepare("SELECT id FROM session_players WHERE session_id=? AND user_id=?");
    $check->execute([$session_id, $bot_id]);
    
    if (!$check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO session_players (session_id, user_id, score_total) VALUES (?, ?, 0)");
        $stmt->execute([$session_id, $bot_id]);
    }
}
echo json_encode(['success' => true]);
?>