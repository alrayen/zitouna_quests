<?php
// Note: Deleted the redundant 'echo "Loaded 1<br>";' from the start

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/quiz-controller.php';
echo "Loaded 1<br>"; 
require_once __DIR__ . '/../Model/quiz.php'; 
echo "Loaded 2<br>";
require_once __DIR__ . '/../Model/question.php';
echo "Loaded 3<br>";

// Check if form data was submitted (adjust method based on your form)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'generate_ai_quiz') {
    
    // --- 1. Sanitize and Collect User Input (MUST COME FIRST) ---
    // The variables are defined here, ensuring they are not NULL.
    $titre = isset($_POST['titre']) && $_POST['titre'] !== '' ? htmlspecialchars($_POST['titre']) : 'AI Quiz (Default)';
    $categorie = isset($_POST['categorie']) && $_POST['categorie'] !== '' ? htmlspecialchars($_POST['categorie']) : 'General (Default)';
    $niveau = isset($_POST['niveau']) && $_POST['niveau'] !== '' ? htmlspecialchars($_POST['niveau']) : 'Medium (Default)';
    
    // Ensure we get an integer, defaulting to 10
    $points = (int)($_POST['points'] ?? 10);
    $questionCount = (int)($_POST['question_count'] ?? 5);

    // --- 2. Execute the Generation ---
    $quizController = new QuizController();
    
    // 🚩 CRUCIAL FIX: CALL THE FUNCTION AND CAPTURE THE $success VARIABLE
    $success = $quizController->generateAndSaveQuiz(
        $titre, 
        $categorie, 
        $niveau, 
        $points, 
        $questionCount
    );

    // --- 3. TEMPORARY DEBUGGING OUTPUT ---
    if ($success) {
        $redirect_url = '/Controller/generation_form.html?status=generation_success';
        echo "<h1>SUCCESSFUL GENERATION!</h1>";
        echo "Quiz generation succeeded and saved to DB.<br>";
        echo "The script *attempted* to redirect to: <strong>" . $redirect_url . "</strong>";
        
        // If you see this, remove the echo and uncomment the header line to test the final redirect.
        // header('Location: ' . $redirect_url); 
    } else {
        $redirect_url = '/Controller/generation_form.html?status=generation_failed';
        echo "<h1>GENERATION FAILED!</h1>";
        echo "The script failed during DB saving or Gemini API call. <br>Attempted redirect to: <strong>" . $redirect_url . "</strong>";
    }
    exit; // Stop execution here
    
} else {
    // Handle direct access or invalid request
    die("Invalid request method.");
}
?>