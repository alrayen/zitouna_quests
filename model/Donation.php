<?php
class Donation {
    private $id;
    private $nom_donateur;
    private $type_don;
    private $montant;
    private $date_don;
    private $etat;
    private $points_gagnes;

    public function __construct($id=null, $nom, $type, $montant, $date, $etat='En attente', $points=0) {
        $this->id = $id;
        $this->nom_donateur = $nom;
        $this->type_don = $type;
        $this->montant = $montant;
        $this->date_don = $date;
        $this->etat = $etat;
        $this->points_gagnes = $points;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNomDonateur() { return $this->nom_donateur; }
    public function getTypeDon() { return $this->type_don; }
    public function getMontant() { return $this->montant; }
    public function getDateDon() { return $this->date_don; }
    public function getEtat() { return $this->etat; }
    public function getPoints() { return $this->points_gagnes; }

    // Setters simples si besoin...
}
?>