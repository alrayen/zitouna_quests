<?php

require_once __DIR__ .'/../config.php';
require_once __DIR__ .'/../Model/question.php'; 

class QuestionController {

    private $db;

    public function __construct() {
        
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
                    $row['id_question'],
                    $row['id_quiz'],
                    $row['text'],
                    $row['option1'],
                    $row['option2'],
                    $row['option3'],
                    $row['option4'],
                    $row['bonne']
                );
            }
            return $questions;
        } catch (PDOException $e) {
            die('Error fetching questions: ' . $e->getMessage());
        }
    }
    


}
?>