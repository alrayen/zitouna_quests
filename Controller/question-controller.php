<?php

// Include the database connection and the Question model
require_once __DIR__ .'../config.php';
require_once __DIR__ .'../Model/question.php'; 

class QuestionController {

    private $db;

    public function __construct() {
        // Get the database connection from your config class
        $this->db = config::getConnexion();
    }

    /**
     * READ: Fetches all questions for a specific quiz ID.
     * @param int $id_quiz The ID of the quiz.
     * @return array An array of Question objects.
     */
    public function getQuestionsForQuiz(int $id_quiz): array {
        $sql = "SELECT * FROM question WHERE Id_quiz = :id_quiz";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll();
            
            $questions = [];
            foreach ($results as $row) {
                $questions[] = new Question(
                    $row['Id_question'],
                    $row['Id_quiz'],
                    $row['Text_question'],
                    $row['Option1'],
                    $row['Option2'],
                    $row['Option3'],
                    $row['Option4'],
                    $row['Bonne_reponse']
                );
            }
            return $questions;
        } catch (PDOException $e) {
            die('Error fetching questions: ' . $e->getMessage());
        }
    }
    
    // --- We can add addQuestion(), updateQuestion(), deleteQuestion() here later ---

}
?>