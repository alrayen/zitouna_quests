<?php
require_once __DIR__ .'/../config.php';
require_once __DIR__ .'/../Model/question.php'; 

class QuestionController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function listQuestionsByQuizId(int $id_quiz): array {
        $sql = "SELECT * FROM question WHERE id_quiz = :id_quiz";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll();
            $questions = [];
            foreach ($results as $row) {
                $questions[] = new Question(
                    $row['id_question'], $row['id_quiz'], $row['text'],
                    $row['option1'], $row['option2'], $row['option3'], $row['option4'],
                    $row['bonne']
                );
            }
            return $questions;
        } catch (PDOException $e) { die('Error: ' . $e->getMessage()); }
    }

    // --- NEW FUNCTIONS START HERE ---
// In question-controller.php

// Change the signature to require boolean return
// In Controller/question-controller.php

public function addQuestion(Question $q): bool { 
    // 1. We use backticks (` `) around "text" because it is a reserved SQL keyword.
    $sql = "INSERT INTO question (id_quiz, `text`, option1, option2, option3, option4, bonne) 
            VALUES (:id, :txt, :o1, :o2, :o3, :o4, :bon)";
            
    $req = $this->db->prepare($sql);
    
    try {
        $result = $req->execute([
            ':id'  => $q->getIdQuiz(), 
            ':txt' => $q->getTextQuestion(),
            ':o1'  => $q->getOption1(), 
            ':o2'  => $q->getOption2(),
            ':o3'  => $q->getOption3(), 
            ':o4'  => $q->getOption4(), 
            ':bon' => $q->getBonneReponse()
        ]);
        return $result; 
    } catch (PDOException $e) {
        // --- DEBUGGING BLOCK START ---
        // This will print the error in big red text and STOP the page.
        echo '<div style="background-color: #ffe6e6; border: 2px solid red; padding: 20px; margin: 20px;">';
        echo '<h2 style="color: red;">❌ SQL INSERT ERROR DETECTED</h2>';
        
        echo '<strong>Database said:</strong> ' . $e->getMessage() . '<br><br>';
        
        echo '<strong>We tried to insert this data:</strong><br>';
        echo 'Quiz ID: ' . $q->getIdQuiz() . '<br>';
        echo 'Question: ' . htmlspecialchars($q->getTextQuestion()) . '<br>';
        echo 'Correct Answer (Bonne): ' . $q->getBonneReponse() . '<br>';
        echo '</div>';
        
        exit(); // STOP SCRIPT HERE so you can read the error
        // --- DEBUGGING BLOCK END ---
    }
}

    public function updateQuestion(Question $q) {
        $sql = "UPDATE question SET text=:txt, option1=:o1, option2=:o2, option3=:o3, option4=:o4, bonne=:bon 
                WHERE id_question=:id";
        $req = $this->db->prepare($sql);
        $req->execute([
            ':id' => $q->getIdQuestion(), ':txt' => $q->getTextQuestion(),
            ':o1' => $q->getOption1(), ':o2' => $q->getOption2(),
            ':o3' => $q->getOption3(), ':o4' => $q->getOption4(), ':bon' => $q->getBonneReponse()
        ]);
    }

    public function deleteQuestion($id) {
        $sql = "DELETE FROM question WHERE id_question = :id";
        $req = $this->db->prepare($sql);
        $req->execute([':id' => $id]);
    }
}
?>