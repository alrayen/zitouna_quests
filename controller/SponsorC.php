<?php
require_once __DIR__ . '/../config/Connect.php';
require_once __DIR__ . '/../model/Sponsor.php';

class SponsorC {

    // AJOUTER
    public function ajouterSponsor($sponsor) {
        $sql = "INSERT INTO sponsor (nom, secteur, contact, contribution) VALUES (:nom, :secteur, :contact, :contribution)";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $sponsor->getNom(),
                'secteur' => $sponsor->getSecteur(),
                'contact' => $sponsor->getContact(),
                'contribution' => $sponsor->getContribution()
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // AFFICHER LISTE
    public function afficherSponsors() {
        $sql = "SELECT * FROM sponsor ORDER BY id DESC";
        $db = Config::getConnexion();
        try { return $db->query($sql); } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // SUPPRIMER
    public function supprimerSponsor($id) {
        $sql = "DELETE FROM sponsor WHERE id = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // RECUPERER (Pour Update)
    public function recupererSponsor($id) {
        $sql = "SELECT * FROM sponsor WHERE id = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // MODIFIER
    public function modifierSponsor($sponsor, $id) {
        $sql = "UPDATE sponsor SET nom=:nom, secteur=:secteur, contact=:contact, contribution=:contribution WHERE id=:id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $sponsor->getNom(),
                'secteur' => $sponsor->getSecteur(),
                'contact' => $sponsor->getContact(),
                'contribution' => $sponsor->getContribution(),
                'id' => $id
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }
}
?>