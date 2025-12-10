<?php
// Controller/start_game.php
session_start();
require_once __DIR__ . '/../config.php';
$pdo = config::getConnexion();

header('Content-Type: application/json');

// 1. Security Checks
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];
$session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;

// Inputs
$category = isset($_POST['category']) ? $_POST['category'] : 'any';
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'any';
// NEW: Check if a specific quiz ID was sent (from AI Generator)
$forced_quiz_id = isset($_POST['forced_quiz_id']) ? (int)$_POST['forced_quiz_id'] : 0;

if ($session_id === 0) {
    echo json_encode(['success' => false, 'message' => 'ID de session manquant']);
    exit;
}

try {
    // 2. Verify User is Host & Game is in LOBBY state
    $stmt = $pdo->prepare("SELECT host_user_id, game_state FROM online_sessions WHERE session_id = ?");
    $stmt->execute([$session_id]);
    $session = $stmt->fetch();

    if (!$session) {
        echo json_encode(['success' => false, 'message' => 'Session introuvable']);
        exit;
    }

    if ($session['host_user_id'] != $user_id) {
        echo json_encode(['success' => false, 'message' => 'Seul l\'hôte peut lancer la partie']);
        exit;
    }

    if ($session['game_state'] !== 'LOBBY') {
        echo json_encode(['success' => false, 'message' => 'La partie a déjà commencé']);
        exit;
    }

    // 3. LOGIC: Choose the Quiz ID
    $selected_quiz_id = 0;

    if ($forced_quiz_id > 0) {
        // CASE A: The Host just generated an AI Quiz, so we use that exact ID
        $selected_quiz_id = $forced_quiz_id;
    } else {
        // CASE B: Random Selection (Your existing logic)
        $sql = "SELECT id_quiz FROM quiz WHERE 1=1";
        $params = [];

        // Filter by Category
        if ($category !== 'any') {
            $sql .= " AND categorie = ?";
            $params[] = $category;
        }

        // Filter by Difficulty
        if ($difficulty !== 'any') {
            if ($difficulty == 'Medium' || $difficulty == 'Moyen') {
                $sql .= " AND (niveau = 'Medium' OR niveau = 'Moyen')";
            } elseif ($difficulty == 'Hard' || $difficulty == 'Difficile') {
                $sql .= " AND (niveau = 'Hard' OR niveau = 'Difficile')";
            } else {
                $sql .= " AND niveau = ?";
                $params[] = $difficulty;
            }
        }

        // Pick 1 Random Result
        $sql .= " ORDER BY RAND() LIMIT 1";

        $quizStmt = $pdo->prepare($sql);
        $quizStmt->execute($params);
        $quiz = $quizStmt->fetch();

        if ($quiz) {
            $selected_quiz_id = $quiz['id_quiz'];
        }
    }

    // Check if we actually have an ID
    if ($selected_quiz_id === 0) {
        echo json_encode(['success' => false, 'message' => 'Aucun quiz trouvé pour cette catégorie.']);
        exit;
    }

    // 4. Update Session: Set Status, Quiz ID, and reset Question Index to 1
    $updateStmt = $pdo->prepare("UPDATE online_sessions SET game_state = 'IN_PROGRESS', current_quiz_id = ?, current_question_index = 1 WHERE session_id = ?");
    $updateStmt->execute([$selected_quiz_id, $session_id]);

    echo json_encode(['success' => true, 'quiz_id' => $selected_quiz_id]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . $e->getMessage()]);
}
?>