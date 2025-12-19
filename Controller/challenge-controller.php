<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/challenge.php';

class ChallengeController {

    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    
    public function listChallenges(): array {
        $sql = "SELECT * FROM challenge";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll();

            $challenges = [];

            foreach ($results as $row) {
                $challenges[] = new Challenge(
                    $row['id_defi'],
                    $row['titre'],
                    $row['description'],
                    $row['categorie'],
                    $row['points'],
                    $row['time'],
                    $row['difficulty'],
                    $row['status'],
                    $row['place']
                );
            }

            return $challenges;

        } catch (PDOException $e) {
            die('Error fetching challenges: ' . $e->getMessage());
        }
    }

    
    public function getChallengeById(int $id): ?Challenge {
        $sql = "SELECT * FROM challenge WHERE id_defi = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();

            if ($row) {
                return new Challenge(
                    $row['id_defi'],
                    $row['titre'],
                    $row['description'],
                    $row['categorie'],
                    $row['points'],
                    $row['time'],
                    $row['difficulty'],
                    $row['status'],
                    $row['place']
                );
            }
            return null;
        } catch (PDOException $e) {
            die('Error fetching challenge: ' . $e->getMessage());
        }
    }

   
    public function addChallenge(Challenge $challenge): bool {
        $sql = "INSERT INTO challenge (titre, description, categorie, points, time, difficulty, status, place) 
                VALUES (:titre, :description, :categorie, :points, :time, :difficulty, :status, :place)";
        try {
            $query = $this->db->prepare($sql);

            $query->bindValue(':titre', $challenge->getTitre());
            $query->bindValue(':description', $challenge->getDescription());
            $query->bindValue(':categorie', $challenge->getCategorie());
            $query->bindValue(':points', $challenge->getPoints(), PDO::PARAM_INT);
            $query->bindValue(':time', $challenge->getTime(), PDO::PARAM_INT);
            $query->bindValue(':difficulty', $challenge->getDifficulty());
            $query->bindValue(':status', $challenge->getStatus());
            $query->bindValue(':place', $challenge->getPlace());

            return $query->execute();
        } catch (PDOException $e) {
            die('Error adding challenge: ' . $e->getMessage());
            return false;
        }
    }

   
    public function updateChallenge(Challenge $challenge): bool {
        $sql = "UPDATE challenge 
                SET titre = :titre, 
                    description = :description,
                    categorie = :categorie, 
                    points = :points,
                    time = :time,
                    difficulty = :difficulty,
                    status = :status,
                    place = :place
                WHERE id_defi = :id";
        try {
            $query = $this->db->prepare($sql);

            $query->bindValue(':titre', $challenge->getTitre());
            $query->bindValue(':description', $challenge->getDescription());
            $query->bindValue(':categorie', $challenge->getCategorie());
            $query->bindValue(':points', $challenge->getPoints(), PDO::PARAM_INT);
            $query->bindValue(':time', $challenge->getTime(), PDO::PARAM_INT);
            $query->bindValue(':difficulty', $challenge->getDifficulty());
            $query->bindValue(':status', $challenge->getStatus());
            $query->bindValue(':place', $challenge->getPlace());
            
            $query->bindValue(':id', $challenge->getIdDefi(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error updating challenge: ' . $e->getMessage());
            return false;
        }
    }

    
    public function deleteChallenge(int $id): bool {
        $sql = "DELETE FROM challenge WHERE id_defi = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            die('Error deleting challenge: ' . $e->getMessage());
            return false;
        }
    }
}
?>