<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/question-controller.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Erreur inconnue'];

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
$question_index = isset($_POST['question_index']) ? (int)$_POST['question_index'] : 0;
$option_id = isset($_POST['option_id']) ? (int)$_POST['option_id'] : 0; 

try {
    $pdo = config::getConnexion();

    $checkStmt = $pdo->prepare("SELECT id FROM session_answers WHERE session_id=? AND user_id=? AND question_index=?");
    $checkStmt->execute([$session_id, $user_id, $question_index]);
    if ($checkStmt->fetch()) {
        throw new Exception('Déjà répondu');
    }

    $sessStmt = $pdo->prepare("SELECT current_quiz_id FROM online_sessions WHERE session_id = ?");
    $sessStmt->execute([$session_id]);
    $session = $sessStmt->fetch();
    
    if (!$session) throw new Exception("Session invalide");
    
    $quiz_id = $session['current_quiz_id'];

    if (!class_exists('QuestionController')) {
        throw new Exception("Controller introuvable");
    }
    
    $qController = new QuestionController();
    $questions = $qController->listQuestionsByQuizId($quiz_id);

    $array_index = $question_index - 1;
    if (!isset($questions[$array_index])) {
        throw new Exception("Question introuvable");
    }

    $q = $questions[$array_index]; 

    $selected_text = "";
    if ($option_id == 1) $selected_text = $q->getOption1();
    if ($option_id == 2) $selected_text = $q->getOption2();
    if ($option_id == 3) $selected_text = $q->getOption3();
    if ($option_id == 4) $selected_text = $q->getOption4();

    $correct_text = $q->getBonneReponse();

    $is_correct = (trim($selected_text) === trim($correct_text));
    $points = $is_correct ? 10 : 0; 

    $insertStmt = $pdo->prepare("INSERT INTO session_answers (session_id, user_id, question_index, submitted_answer, is_correct, points_earned) VALUES (?, ?, ?, ?, ?, ?)");
    $insertStmt->execute([$session_id, $user_id, $question_index, utf8_encode($selected_text), $is_correct ? 1 : 0, $points]);

    if ($is_correct) {
        $scoreStmt = $pdo->prepare("UPDATE session_players SET score_total = score_total + ? WHERE session_id = ? AND user_id = ?");
        $scoreStmt->execute([$points, $session_id, $user_id]);
    }

    $collection_unlocked = false;
    $total_questions = count($questions);

    if ($question_index >= $total_questions) {
        
        // 1. Fetch Quiz Title (Needed for the Image Prompt)
        $titleStmt = $pdo->prepare("SELECT titre FROM quiz WHERE id_quiz = ?");
        $titleStmt->execute([$quiz_id]);
        $quiz_title = $titleStmt->fetchColumn();

        if ($quiz_title) {
            // 2. Generate the Same URL as the Frontend
            $prompt = urlencode($quiz_title . " epic futuristic video game poster style high resolution detailed");
            $imageUrl = "https://image.pollinations.ai/prompt/$prompt?width=300&height=450&nologo=true";

            // 3. Check if user already owns this collection
            $checkColl = $pdo->prepare("SELECT id FROM user_collections WHERE user_id = ? AND quiz_id = ?");
            $checkColl->execute([$user_id, $quiz_id]);

            if (!$checkColl->fetch()) {
                // 4. Save to Database
                $insColl = $pdo->prepare("INSERT INTO user_collections (user_id, quiz_id, image_url, collected_at) VALUES (?, ?, ?, NOW())");
                $insColl->execute([$user_id, $quiz_id, $imageUrl]);
                
                $collection_unlocked = true;
            }
        }
    }

    $response = [
        'success' => true,
        'correct' => $is_correct,
        'points' => $points,
        // Send this flag so the JS triggers the "Grand Reveal" animation
        'collection_unlocked' => $collection_unlocked 
    ];

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

ob_end_clean();
echo json_encode($response);
?>