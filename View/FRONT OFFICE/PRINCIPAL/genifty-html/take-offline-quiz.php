<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// 1. VALIDATION & SETUP
// Redirect if no game is in session or if quiz ID is missing
if (!isset($_SESSION['offline_game']) || !isset($_GET['quiz'])) {
    header('Location: offline-quiz.php');
    exit();
}

// Include controllers
$quizControllerPath = '../../../../Controller/quiz-controller.php';
$questionControllerPath = '../../../../Controller/question-controller.php';

if (!file_exists($quizControllerPath) || !file_exists($questionControllerPath)) {
    $_SESSION['error'] = "FATAL ERROR: A required controller file is missing.";
    header('Location: start-offline-game.php');
    exit();
}
require_once $quizControllerPath;
require_once $questionControllerPath;

$quizId = (int)$_GET['quiz'];
$gameData = $_SESSION['offline_game'];
$currentTurn = $gameData['current_turn'];
$teamName = $gameData['teams'][$currentTurn]['name'];

// Fetch quiz and question details
try {
    $quizCtrl = new QuizController();
    $questionCtrl = new QuestionController();

    $quiz = $quizCtrl->getQuizById($quizId);
    $questions = $questionCtrl->listQuestionsByQuizId($quizId);

    if (!$quiz || empty($questions)) {
        throw new Exception("Quiz or questions not found for ID: " . $quizId);
    }
    // For simplicity, we'll use the first question of the quiz
    $question = $questions[0];

} catch (Exception $e) {
    $_SESSION['error'] = "Error loading quiz: " . $e->getMessage();
    header('Location: start-offline-game.php');
    exit();
}

// 2. HANDLE FORM SUBMISSION (ANSWERING THE QUIZ)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $submittedAnswer = $_POST['answer'];
        $correctAnswer = $question->getBonneReponse();
        $quizPoints = $quiz->getPoints();

        // Check if the answer is correct
        if ($submittedAnswer === $correctAnswer) {
            // Add points to the current team
            $_SESSION['offline_game']['teams'][$currentTurn]['score'] += $quizPoints;
        }

        // Mark the quiz as completed
        foreach ($_SESSION['offline_game']['quizzes'] as $categorySlug => &$categoryQuizzes) {
            if ($categoryQuizzes['q1_id'] == $quizId) {
                $categoryQuizzes['q1_status'] = 'completed';
                break;
            }
            if ($categoryQuizzes['q2_id'] == $quizId) {
                $categoryQuizzes['q2_status'] = 'completed';
                break;
            }
        }

        // Switch turns
        $_SESSION['offline_game']['current_turn'] = ($currentTurn == 1) ? 2 : 1;

        // Redirect back to the game board
        header('Location: start-offline-game.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zitouna Quests - Take Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b);
            background-size: 400% 400%;
            animation: moveGradient 20s ease infinite;
            color: #fff;
            font-family: 'Gordita', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        .quiz-container {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 40px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .quiz-header {
            margin-bottom: 30px;
        }
        .quiz-header .turn-info {
            font-size: 1.2rem;
            color: #FFBB28;
            font-weight: 600;
        }
        .quiz-header .quiz-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-top: 10px;
        }
        .quiz-header .points-badge {
            background: #FFBB28;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 700;
            display: inline-block;
            margin-top: 15px;
        }

        .question-text {
            font-size: 1.8rem;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .answers-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .answer-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 20px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .answer-btn:hover {
            background: rgba(0, 196, 159, 0.5);
            border-color: #00C49F;
            transform: translateY(-5px);
        }

        .answer-btn:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0, 196, 159, 0.7);
        }
    </style>
</head>
<body>

    <div class="quiz-container">
        <div class="quiz-header">
            <p class="turn-info">Au tour de : <?php echo htmlspecialchars($teamName); ?></p>
            <h1 class="quiz-title"><?php echo htmlspecialchars($quiz->getTitre()); ?></h1>
            <span class="points-badge">+<?php echo htmlspecialchars($quiz->getPoints()); ?> Points</span>
        </div>

        <p class="question-text"><?php echo htmlspecialchars($question->getTextQuestion()); ?></p>

        <form method="POST" action="">
            <div class="answers-grid">
                <button type="submit" name="answer" value="<?php echo htmlspecialchars($question->getOption1()); ?>" class="answer-btn">
                    <?php echo htmlspecialchars($question->getOption1()); ?>
                </button>
                <button type="submit" name="answer" value="<?php echo htmlspecialchars($question->getOption2()); ?>" class="answer-btn">
                    <?php echo htmlspecialchars($question->getOption2()); ?>
                </button>
                <button type="submit" name="answer" value="<?php echo htmlspecialchars($question->getOption3()); ?>" class="answer-btn">
                    <?php echo htmlspecialchars($question->getOption3()); ?>
                </button>
                <button type="submit" name="answer" value="<?php echo htmlspecialchars($question->getOption4()); ?>" class="answer-btn">
                    <?php echo htmlspecialchars($question->getOption4()); ?>
                </button>
            </div>
        </form>
    </div>

</body>
</html>