<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Donation.php';

class DonationC {

    // MODIFICATION : On ajoute le sponsor_id dans l'insertion
    public function createDonation($donation, $sponsor_id) {
        $sql = "INSERT INTO donation (nom_donateur, type_don, montant, date_don, etat, points_gagnes, sponsor_id) 
                VALUES (:nom, :type, :montant, :date, 'En attente', 0, :sid)";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $donation->getNomDonateur(),
                'type' => $donation->getTypeDon(),
                'montant' => $donation->getMontant(),
                'date' => $donation->getDateDon(),
                'sid' => $sponsor_id  // <--- NOUVEAU
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // AFFICHER TOUT (On fait une JOINTURE pour afficher le nom du sponsor proprement)
    public function listDonations() {
        // Cette requête récupère le nom du sponsor depuis la table sponsor grâce à l'ID
        $sql = "SELECT d.*, s.nom as nom_reel_sponsor 
                FROM donation d 
                LEFT JOIN sponsor s ON d.sponsor_id = s.id 
                ORDER BY d.date_don DESC";
        $db = Config::getConnexion();
        try { return $db->query($sql); } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // ... Garde les autres fonctions (validate, delete) comme avant ...
    public function validateDonation($id, $montant) {
        $points = floor($montant / 10); 
        $sql = "UPDATE donation SET etat = 'Validé', points_gagnes = :points WHERE id = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['points' => $points, 'id' => $id]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // SUPPRIMER UN DON
    public function deleteDonation($id) {
        $sql = "DELETE FROM donation WHERE id = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) {
            die('Erreur SQL lors de la suppression : ' . $e->getMessage());
        }
    }

    // RECUPERER LES DONS D'UN SPONSOR SPECIFIQUE
    public function recupererDonsParSponsor($sponsor_id) {
        $sql = "SELECT * FROM donation WHERE sponsor_id = :id ORDER BY date_don DESC";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $sponsor_id);
            $query->execute();
            return $query->fetchAll(); // On veut toutes les lignes
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }
}
?>