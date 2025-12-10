<?php
require_once __DIR__ . '/../config.php'; 

// Make sure this filename matches your actual file (e.g. sponsor.php or Sponsor.php)
require_once __DIR__ . '/../Model/sponsor.php';

class SponsorController {

    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    /**
     * READ: Fetches all sponsors from the database.
     * @return array An array of Sponsor objects.
     */
    public function listSponsors(): array {
        $sql = "SELECT * FROM sponsor";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            $results = $query->fetchAll();

            $sponsors = [];

            foreach ($results as $row) {
                // CAREFUL: The order of arguments must match the __construct in Sponsor.php
                // Order: nom, secteur, contact, contribution, id
                $sponsors[] = new Sponsor(
                    $row['nom'],
                    $row['secteur'],
                    $row['contact'],
                    $row['contribution'],
                    $row['id']
                );
            }
                 
            return $sponsors;

        } catch (PDOException $e) {
             
            die('Error fetching sponsors: ' . $e->getMessage());
        }
       
    }

    /**
     * READ: Fetches a single sponsor by its ID.
     * @param int $id The ID of the sponsor to fetch.
     * @return Sponsor|null A Sponsor object if found, otherwise null.
     */
    public function getSponsorById(int $id): ?Sponsor {
        $sql = "SELECT * FROM sponsor WHERE id = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();

            if ($row) {
                return new Sponsor(
                    $row['nom'],
                    $row['secteur'],
                    $row['contact'],
                    $row['contribution'],
                    $row['id']
                );
            }
            return null;
        } catch (PDOException $e) {
            die('Error fetching sponsor: ' . $e->getMessage());
        }
    }

    /**
     * CREATE: Adds a new sponsor to the database.
     * @param Sponsor $sponsor A Sponsor object populated with the new data.
     * @return bool True on success, false on failure.
     */
    public function addSponsor(Sponsor $sponsor): bool {
        $sql = "INSERT INTO sponsor (nom, secteur, contact, contribution) 
                VALUES (:nom, :secteur, :contact, :contribution)";
        try {
            $query = $this->db->prepare($sql);
            
            $query->bindValue(':nom', $sponsor->getNom());
            $query->bindValue(':secteur', $sponsor->getSecteur());
            $query->bindValue(':contact', $sponsor->getContact());
            $query->bindValue(':contribution', $sponsor->getContribution());

            return $query->execute();
        } catch (PDOException $e) {
            die('Error adding sponsor: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * UPDATE: Updates an existing sponsor in the database.
     * @param Sponsor $sponsor A Sponsor object with the ID and updated data.
     * @return bool True on success, false on failure.
     */
    public function updateSponsor(Sponsor $sponsor): bool {
        $sql = "UPDATE sponsor 
                SET nom = :nom, 
                    secteur = :secteur, 
                    contact = :contact, 
                    contribution = :contribution 
                WHERE id = :id";
        try {
            $query = $this->db->prepare($sql);

            $query->bindValue(':nom', $sponsor->getNom());
            $query->bindValue(':secteur', $sponsor->getSecteur());
            $query->bindValue(':contact', $sponsor->getContact());
            $query->bindValue(':contribution', $sponsor->getContribution());
            $query->bindValue(':id', $sponsor->getId(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            die('Error updating sponsor: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Deletes a sponsor from the database.
     * @param int $id The ID of the sponsor to delete.
     * @return bool True on success, false on failure.
     */ 
    public function deleteSponsor(int $id): bool {
        $sql = "DELETE FROM sponsor WHERE id = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            die('Error deleting sponsor: ' . $e->getMessage());
            return false;
        }
    }
}
?>