<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/question-controller.php';

header('Content-Type: application/json');
$pdo = config::getConnexion();

$session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
$question_index = isset($_POST['question_index']) ? (int)$_POST['question_index'] : 0;
$total_questions = isset($_POST['total_questions']) ? (int)$_POST['total_questions'] : 10;
$bot_id = 999;

try {
    $check = $pdo->prepare("SELECT id FROM session_players WHERE session_id=? AND user_id=?");
    $check->execute([$session_id, $bot_id]);
    if(!$check->fetch()) { throw new Exception("No bot here"); }

    $checkAns = $pdo->prepare("SELECT id FROM session_answers WHERE session_id=? AND user_id=? AND question_index=?");
    $checkAns->execute([$session_id, $bot_id, $question_index]);
    if($checkAns->fetch()) { throw new Exception("Already answered"); }

    $sessStmt = $pdo->prepare("SELECT current_quiz_id FROM online_sessions WHERE session_id = ?");
    $sessStmt->execute([$session_id]);
    $quiz_id = $sessStmt->fetchColumn();

    $qController = new QuestionController();
    $questions = $qController->listQuestionsByQuizId($quiz_id);
    $q = $questions[$question_index - 1];

    
    $progress = $question_index / $total_questions; 
    $win_chance = 30 + ($progress * 60); 
    
    $roll = rand(0, 100);
    $is_correct = ($roll <= $win_chance);

   
    $submitted_answer = "";
    $correct_text = $q->getBonneReponse();

    if ($is_correct) {
        $submitted_answer = $correct_text;
    } else {
        
        $options = [$q->getOption1(), $q->getOption2(), $q->getOption3(), $q->getOption4()];
      
        $wrong_options = array_filter($options, function($opt) use ($correct_text) {
             return trim($opt) !== trim($correct_text);
        });
        $submitted_answer = !empty($wrong_options) ? $wrong_options[array_rand($wrong_options)] : "Error";
    }

    $points = $is_correct ? 10 : 0;
    $insert = $pdo->prepare("INSERT INTO session_answers (session_id, user_id, question_index, submitted_answer, is_correct, points_earned) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->execute([$session_id, $bot_id, $question_index, utf8_encode($submitted_answer), $is_correct ? 1 : 0, $points]);

    if ($is_correct) {
        $upd = $pdo->prepare("UPDATE session_players SET score_total = score_total + ? WHERE session_id = ? AND user_id = ?");
        $upd->execute([$points, $session_id, $bot_id]);
    }

    echo json_encode(['success' => true, 'bot_correct' => $is_correct]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
ob_end_clean();
?>