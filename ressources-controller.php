<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/ressources-model.php';

class RessourceController {

    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    /**
     * READ: Fetches all resources.
     */
    public function listResources(): array {
        $sql = "SELECT * FROM ressource";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll();

            $resources = [];

            foreach ($results as $row) {
                $resources[] = new Ressource(
                    $row['id_ressource'],
                    $row['nom'],
                    $row['type'],
                    $row['url'],
                    $row['description'],
                    $row['id_defi'],
                    $row['ordre'],
                    (bool)$row['necessite_preuve']
                );
            }

            return $resources;

        } catch (PDOException $e) {
            die('Error fetching resources: ' . $e->getMessage());
        }
    }

    /**
     * READ: Fetches a single resource by ID.
     */
    public function getResourceById(int $id): ?Ressource {
        $sql = "SELECT * FROM ressource WHERE id_ressource = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();

            if ($row) {
                return new Ressource(
                    $row['id_ressource'],
                    $row['nom'],
                    $row['type'],
                    $row['url'],
                    $row['description'],
                    $row['id_defi'],
                    $row['ordre'],
                    (bool)$row['necessite_preuve']
                );
            }
            return null;
        } catch (PDOException $e) {
            die('Error fetching resource: ' . $e->getMessage());
        }
    }

    /**
     * CREATE: Matches UML method "addResource"
     */
    public function addResource(Ressource $ressource): bool {
        $sql = "INSERT INTO ressource (nom, type, url, description, id_defi, ordre, necessite_preuve) 
                VALUES (:nom, :type, :url, :description, :id_defi, :ordre, :necessite_preuve)";
        try {
            $query = $this->db->prepare($sql);

            $query->bindValue(':nom', $ressource->getNom());
            $query->bindValue(':type', $ressource->getType());
            $query->bindValue(':url', $ressource->getUrl());
            $query->bindValue(':description', $ressource->getDescription());
            $query->bindValue(':id_defi', $ressource->getIdDefi(), PDO::PARAM_INT);
            $query->bindValue(':ordre', $ressource->getOrdre(), PDO::PARAM_INT);
            $query->bindValue(':necessite_preuve', $ressource->getNecessitePreuve(), PDO::PARAM_BOOL);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error adding resource: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * UPDATE: Matches UML method "updateResource"
     */
    public function updateResource(Ressource $ressource): bool {
        $sql = "UPDATE ressource 
                SET nom = :nom, 
                    type = :type,
                    url = :url, 
                    description = :description,
                    id_defi = :id_defi,
                    ordre = :ordre,
                    necessite_preuve = :necessite_preuve
                WHERE id_ressource = :id";
        try {
            $query = $this->db->prepare($sql);

            $query->bindValue(':nom', $ressource->getNom());
            $query->bindValue(':type', $ressource->getType());
            $query->bindValue(':url', $ressource->getUrl());
            $query->bindValue(':description', $ressource->getDescription());
            $query->bindValue(':id_defi', $ressource->getIdDefi(), PDO::PARAM_INT);
            $query->bindValue(':ordre', $ressource->getOrdre(), PDO::PARAM_INT);
            $query->bindValue(':necessite_preuve', $ressource->getNecessitePreuve(), PDO::PARAM_BOOL);
            
            $query->bindValue(':id', $ressource->getIdRessource(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error updating resource: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Matches UML method "deleteResource"
     */
    public function deleteResource(int $id): bool {
        $sql = "DELETE FROM ressource WHERE id_ressource = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            die('Error deleting resource: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NEW: Fetch all resources linked to a specific Challenge ID
     * @param int $id_defi The ID of the challenge
     * @return array An array of Ressource objects
     */
    public function getResourcesByDefiId(int $id_defi): array {
        $sql = "SELECT * FROM ressource WHERE id_defi = :id_defi ORDER BY ordre ASC";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id_defi', $id_defi, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll();

            $resources = [];

            foreach ($results as $row) {
                $resources[] = new Ressource(
                    $row['id_ressource'],
                    $row['nom'],
                    $row['type'],
                    $row['url'],
                    $row['description'],
                    $row['id_defi'],
                    $row['ordre'],
                    (bool)$row['necessite_preuve']
                );
            }

            return $resources;

        } catch (PDOException $e) {
            die('Error fetching resources for challenge: ' . $e->getMessage());
        }
    }
}
?>