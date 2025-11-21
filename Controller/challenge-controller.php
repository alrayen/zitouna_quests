<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/challenge.php';

class ChallengeController {

    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    /**
     * READ: Fetches all challenges from the database.
     * @return array An array of Challenge objects.
     */
    public function listChallenges(): array {
        // Assuming table name is 'challenge' based on context. Change to 'challenges' if needed.
        $sql = "SELECT * FROM challenge";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll();

            $challenges = [];

            foreach ($results as $row) {
                // The order here must match the __construct order in your Challenge model
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

    /**
     * READ: Fetches a single challenge by its ID.
     * @param int $id The ID of the challenge to fetch.
     * @return Challenge|null A Challenge object if found, otherwise null.
     */
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

    /**
     * CREATE: Adds a new challenge to the database.
     * @param Challenge $challenge A Challenge object populated with the new data.
     * @return bool True on success, false on failure.
     */
    public function addChallenge(Challenge $challenge): bool {
        // Note: We do not insert 'id_defi' because it is AUTO_INCREMENT
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

    /**
     * UPDATE: Updates an existing challenge in the database.
     * @param Challenge $challenge A Challenge object with the ID and updated data.
     * @return bool True on success, false on failure.
     */
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
            
            // Bind the ID for the WHERE clause
            $query->bindValue(':id', $challenge->getIdDefi(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error updating challenge: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Deletes a challenge from the database.
     * @param int $id The ID of the challenge to delete.
     * @return bool True on success, false on failure.
     */
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