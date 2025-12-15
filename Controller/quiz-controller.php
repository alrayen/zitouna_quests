<?php
require_once __DIR__ . '/../config.php'; 
require_once __DIR__ . '/../Model/quiz.php';
require_once __DIR__ . '/../Model/question.php'; 
require_once __DIR__ . '/AIService.php';
require_once __DIR__ . '/question-controller.php';

class QuizController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function generateAndSaveQuiz(string $titre, string $categorie, string $niveau, int $points, int $questionCount): bool {
                $aiService = new AIService();
        $generatedQuestionsData = $aiService->generateQuiz($titre, $niveau, $questionCount);
        
        if (empty($generatedQuestionsData)) {
            error_log("Failed to generate questions using AI.");
            return false;
        }
        $newQuiz = new Quiz(null, $titre, $categorie, $niveau, $points);
        if (!$this->addQuiz($newQuiz)) {
            error_log("Failed to save the new quiz record.");
            return false;
        }
        $newQuizId = $this->db->lastInsertId();
if ($newQuizId <= 0) {
echo "<h3>❌ FATAL ERROR: Quiz ID Retrieval Failed!</h3>";
echo "The last inserted ID was: " . $newQuizId;
return false;
}

        $questionController = new QuestionController();
        $successfulInserts = 0;
        $totalQuestions = count($generatedQuestionsData);

       foreach ($generatedQuestionsData as $index => $data) {
    if (isset($data['text'], $data['option1'], $data['bonne'])) {
        $question = new Question(
            null,
            $newQuizId, 
            $data['text'],
            $data['option1'],
            $data['option2'] ?? '',
            $data['option3'] ?? '',
            $data['option4'] ?? '',
            $data['bonne']
        );

        $insert_success = $questionController->addQuestion($question);
        
        if ($insert_success === true) {
            $successfulInserts++;
        } else {
            echo "Failed to insert question index: $index <br>";
        }
    } else {
        error_log("Skipping malformed question data: " . json_encode($data));
    }
}

if ($successfulInserts == 0) {
     echo "<h3>🛑 ALL INSERTS FAILED</h3>";
     return false; 
}

return true;
    }

    public function listQuizzes(): array {
        $sql = "SELECT * FROM quiz";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll();

            $quizzes = [];
            foreach ($results as $row) {
                $quizzes[] = new Quiz(
                    $row['id_quiz'],
                    $row['titre'],
                    $row['categorie'],
                    $row['niveau'],
                    $row['points']
                );
            }
            return $quizzes;

        } catch (PDOException $e) {
            die('Error fetching quizzes: ' . $e->getMessage());
        }
    }

    public function getQuizById(int $id): ?Quiz {
        $sql = "SELECT * FROM quiz WHERE Id_quiz = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();

            if ($row) {
                return new Quiz(
                    $row['id_quiz'],
                    $row['titre'],
                    $row['categorie'],
                    $row['niveau'],
                    $row['points']
                );
            }
            return null;
        } catch (PDOException $e) {
            die('Error fetching quiz: ' . $e->getMessage());
        }
    }

    public function addQuiz(Quiz $quiz): bool {
        $sql = "INSERT INTO quiz (titre, categorie, niveau, points) 
                 VALUES (:titre, :categorie, :niveau, :points)";
        try {
            $query = $this->db->prepare($sql);
            
            $query->bindValue(':titre', $quiz->getTitre());
            $query->bindValue(':categorie', $quiz->getCategorie());
            $query->bindValue(':niveau', $quiz->getNiveau());
            $query->bindValue(':points', $quiz->getPoints(), PDO::PARAM_INT);
            
            return $query->execute();
        } catch (PDOException $e) {
            // 🚩 TEMPORARY DEBUGGING CHANGE 🚩
            echo '<h3>❌ QUIZ INSERTION FAILED!</h3>';
            echo '<strong>Database Error: </strong>' . $e->getMessage() . '<br>';
            return false;
        }
    }

    public function updateQuiz(Quiz $quiz): bool {
        $sql = "UPDATE quiz 
                SET titre = :titre, 
                    categorie = :categorie, 
                    niveau = :niveau, 
                    points = :points 
                WHERE Id_quiz = :id";
        try {
            $query = $this->db->prepare($sql);

            $query->bindValue(':titre', $quiz->getTitre());
            $query->bindValue(':categorie', $quiz->getCategorie());
            $query->bindValue(':niveau', $quiz->getNiveau());
            $query->bindValue(':points', $quiz->getPoints(), PDO::PARAM_INT);
            $query->bindValue(':id', $quiz->getIdQuiz(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error updating quiz: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteQuiz(int $id): bool {
        $sql = "DELETE FROM quiz WHERE Id_quiz = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            die('Error deleting quiz: ' . $e->getMessage());
            return false;
        }
    }
}
?>