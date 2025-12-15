<?php
// Controller/next_question.php
ob_start(); // Prevent garbage text
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Erreur inconnue'];

try {
    $session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
    $user_id = $_SESSION['user_id'] ?? 0;

    if ($session_id === 0 || $user_id === 0) throw new Exception("Session ou User manquant");

    $pdo = config::getConnexion();

    // Verify Host
    $stmt = $pdo->prepare("SELECT host_user_id, current_quiz_id, current_question_index FROM online_sessions WHERE session_id = ?");
    $stmt->execute([$session_id]);
    $session = $stmt->fetch();

    if ($session && $session['host_user_id'] == $user_id) {
        
        // Count total questions
        $qStmt = $pdo->prepare("SELECT COUNT(*) FROM question WHERE id_quiz = ?");
        $qStmt->execute([$session['current_quiz_id']]);
        $totalQuestions = $qStmt->fetchColumn();

        if ($session['current_question_index'] >= $totalQuestions) {
            // End Game
            $update = $pdo->prepare("UPDATE online_sessions SET game_state = 'ENDED' WHERE session_id = ?");
            $update->execute([$session_id]);
        } else {
            // Increment Question Index
            $update = $pdo->prepare("UPDATE online_sessions SET current_question_index = current_question_index + 1 WHERE session_id = ?");
            $update->execute([$session_id]);
        }
        $response = ['success' => true];
    } else {
        throw new Exception("Vous n'êtes pas l'hôte");
    }

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

ob_end_clean(); // Clean buffer
echo json_encode($response);
?>