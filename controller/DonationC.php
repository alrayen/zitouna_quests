<?php
require_once __DIR__ . '/../config/Connect.php';
require_once __DIR__ . '/../model/Donation.php';

class DonationC {

    // 1. CRÉER UN DON (createDonation)
    public function createDonation($donation) {
        $sql = "INSERT INTO donation (nom_donateur, type_don, montant, date_don, etat, points_gagnes) 
                VALUES (:nom, :type, :montant, :date, 'En attente', 0)";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $donation->getNomDonateur(),
                'type' => $donation->getTypeDon(),
                'montant' => $donation->getMontant(),
                'date' => $donation->getDateDon()
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // 2. AFFICHER TOUT (Historique des dons)
    public function listDonations() {
        $sql = "SELECT * FROM donation ORDER BY date_don DESC";
        $db = Config::getConnexion();
        try { return $db->query($sql); } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // 3. VALIDER ET ATTRIBUER POINTS (validateDonation + assignUserPoints)
    public function validateDonation($id, $montant) {
        // Logique métier : 10 DT = 1 Point
        $points = floor($montant / 10); 

        $sql = "UPDATE donation SET etat = 'Validé', points_gagnes = :points WHERE id = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'points' => $points,
                'id' => $id
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // 4. SUPPRIMER
    public function deleteDonation($id) {
        $sql = "DELETE FROM donation WHERE id = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }
}
?>