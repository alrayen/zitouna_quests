<?php
class Sponsor {
    private $id;
    private $nom;
    private $secteur;
    private $contact;
    private $contribution;

    public function __construct($id = null, $nom = null, $secteur = null, $contact = null, $contribution = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->secteur = $secteur;
        $this->contact = $contact;
        $this->contribution = $contribution;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getSecteur() { return $this->secteur; }
    public function getContact() { return $this->contact; }
    public function getContribution() { return $this->contribution; }

    public function setNom($nom) { $this->nom = $nom; }
    public function setSecteur($secteur) { $this->secteur = $secteur; }
    public function setContact($contact) { $this->contact = $contact; }
    public function setContribution($contribution) { $this->contribution = $contribution; }
}
?>