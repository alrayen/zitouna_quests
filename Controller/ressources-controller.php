<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/ressources-model.php';

class RessourceController {

    public function addResource(Ressource $ressource) {
        try {
            $pdo = config::getConnexion();
            $query = "INSERT INTO ressource (nom, type, url, description, id_defi, ordre, necessite_preuve) 
                      VALUES (:nom, :type, :url, :description, :id_defi, :ordre, :necessite_preuve)";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nom' => $ressource->getNom(),
                'type' => $ressource->getType(),
                'url' => $ressource->getUrl(),
                'description' => $ressource->getDescription(),
                'id_defi' => $ressource->getIdDefi(),
                'ordre' => $ressource->getOrdre(),
                'necessite_preuve' => $ressource->getNecessitePreuve() ? 1 : 0
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo "Error adding resource: " . $e->getMessage();
            return false;
        }
    }

    public function updateResource(Ressource $ressource) {
        try {
            $pdo = config::getConnexion();
            $query = "UPDATE ressource SET 
                        nom = :nom, 
                        type = :type, 
                        url = :url, 
                        description = :description, 
                        id_defi = :id_defi,
                        ordre = :ordre,
                        necessite_preuve = :necessite_preuve
                      WHERE id_ressource = :id";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'id' => $ressource->getIdRessource(),
                'nom' => $ressource->getNom(),
                'type' => $ressource->getType(),
                'url' => $ressource->getUrl(),
                'description' => $ressource->getDescription(),
                'id_defi' => $ressource->getIdDefi(),
                'ordre' => $ressource->getOrdre(),
                'necessite_preuve' => $ressource->getNecessitePreuve() ? 1 : 0
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo "Error updating resource: " . $e->getMessage();
            return false;
        }
    }

    public function deleteResource(int $id) {
        try {
            $pdo = config::getConnexion();
            $query = "DELETE FROM ressource WHERE id_ressource = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            echo "Error deleting resource: " . $e->getMessage();
            return false;
        }
    }

    public function listResources() {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM ressource ORDER BY id_defi, ordre";
            $stmt = $pdo->query($query);
            
            $resources = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
            echo "Error listing resources: " . $e->getMessage();
            return [];
        }
    }

    public function getResourcesByDefiId(int $id_defi) {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM ressource WHERE id_defi = :id_defi ORDER BY ordre ASC";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id_defi' => $id_defi]);
            
            $resources = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
            echo "Error fetching resources by challenge: " . $e->getMessage();
            return [];
        }
    }
    
    public function getResourceById(int $id) {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM ressource WHERE id_ressource = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
            return null;
        }
    }
}
?>