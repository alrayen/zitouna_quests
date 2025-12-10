<?php

ob_start();

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/question-controller.php'; 
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

$session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
$question_index = isset($_POST['question_index']) ? (int)$_POST['question_index'] : 1;

$response = ['success' => false, 'message' => 'Erreur inconnue'];

try {
    if ($session_id === 0) {
        throw new Exception('ID de session manquant');
    }

    $pdo = config::getConnexion();
    
    // Check Quiz ID
    $stmt = $pdo->prepare("SELECT current_quiz_id FROM online_sessions WHERE session_id = ?");
    $stmt->execute([$session_id]);
    $result = $stmt->fetch();

    if (!$result || $result['current_quiz_id'] == 0) {
        throw new Exception('Aucun quiz associé à cette session (ID=0)');
    }

    $quiz_id = $result['current_quiz_id'];

    if (!class_exists('QuestionController')) {
        throw new Exception("Classe QuestionController introuvable");
    }

    $qController = new QuestionController();
    $all_questions = $qController->listQuestionsByQuizId($quiz_id);

    // Check if we actually found questions
    if (empty($all_questions)) {
        throw new Exception("Ce quiz (ID: $quiz_id) ne contient aucune question !");
    }

    // Array index is 0-based
    $array_index = $question_index - 1;

    if (isset($all_questions[$array_index])) {
        $q = $all_questions[$array_index];
        $response = [
            'success' => true,
            'question' => [
                'id' => $q->getIdQuestion(),
                'text' => utf8_encode($q->getTextQuestion()), // Ensure French accents work
                'total_questions' => count($all_questions)
            ],
            'options' => [
                ['option_id' => 1, 'text' => utf8_encode($q->getOption1())],
                ['option_id' => 2, 'text' => utf8_encode($q->getOption2())],
                ['option_id' => 3, 'text' => utf8_encode($q->getOption3())],
                ['option_id' => 4, 'text' => utf8_encode($q->getOption4())]
            ]
        ];
    } else {
        throw new Exception('Index de question hors limites');
    }

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

ob_end_clean(); 
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>