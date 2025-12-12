<?php


require_once __DIR__ .'../config.php';
require_once __DIR__ .'../Model/question.php'; 

class QuestionController {

    private $db;

    public function __construct() {
        
        $this->db = config::getConnexion();
    }


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
    
    

}
?>