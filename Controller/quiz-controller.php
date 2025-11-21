<?php
require_once __DIR__ . '/../config.php'; 

// Go up ONE level, then down into 'Model'
require_once __DIR__ . '/../Model/quiz.php';
class QuizController {

    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    /**
     * READ: Fetches all quizzes from the database.
     * @return array An array of Quiz objects.
     */
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

    /**
     * READ: Fetches a single quiz by its ID.
     * @param int $id The ID of the quiz to fetch.
     * @return Quiz|null A Quiz object if found, otherwise null.
     */
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

    /**
     * CREATE: Adds a new quiz to the database.
     * @param Quiz $quiz A Quiz object populated with the new data.
     * @return bool True on success, false on failure.
     */
    public function addQuiz(Quiz $quiz): bool {
        $sql = "INSERT INTO quiz (titre, categorie, niveau, points) 
                VALUES (:titre, :categorie, :niveau, :points)";
        try {
            $query = $this->db->prepare($sql);
            
            // Bind values from the Quiz object's getters
            $query->bindValue(':titre', $quiz->getTitre());
            $query->bindValue(':categorie', $quiz->getCategorie());
            $query->bindValue(':niveau', $quiz->getNiveau());
            $query->bindValue(':points', $quiz->getPoints(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error adding quiz: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * UPDATE: Updates an existing quiz in the database.
     * @param Quiz $quiz A Quiz object with the ID and updated data.
     * @return bool True on success, false on failure.
     */
    public function updateQuiz(Quiz $quiz): bool {
        $sql = "UPDATE quiz 
                SET titre = :titre, 
                    categorie = :categorie, 
                    niveau = :niveau, 
                    points = :points 
                WHERE Id_quiz = :id";
        try {
            $query = $this->db->prepare($sql);

            // Bind all values, including the ID
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

    /**
     * DELETE: Deletes a quiz from the database.
     * @param int $id The ID of the quiz to delete.
     * @return bool True on success, false on failure.
     */
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