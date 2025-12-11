<?php
// Model/ressources-model.php

require_once __DIR__ . '/../config.php';

class Ressource {
    private int $id_ressource;
    private string $nom;
    private string $type;
    private string $url;
    private string $description;
    private int $id_defi;
    private int $ordre;
    private bool $necessite_preuve;

    // Constructor
    public function __construct(
        int $id_ressource = 0, 
        string $nom = "", 
        string $type = "", 
        string $url = "", 
        string $description = "", 
        int $id_defi = 0, 
        int $ordre = 1,
        bool $necessite_preuve = false
    ) {
        $this->id_ressource = $id_ressource;
        $this->nom = $nom;
        $this->type = $type;
        $this->url = $url;
        $this->description = $description;
        $this->id_defi = $id_defi;
        $this->ordre = $ordre;
        $this->necessite_preuve = $necessite_preuve;
    }

    // Getters
    public function getIdRessource(): int { return $this->id_ressource; }
    public function getNom(): string { return $this->nom; }
    public function getType(): string { return $this->type; }
    public function getUrl(): string { return $this->url; }
    public function getDescription(): string { return $this->description; }
    public function getIdDefi(): int { return $this->id_defi; }
    public function getOrdre(): int { return $this->ordre; }
    public function getNecessitePreuve(): bool { return $this->necessite_preuve; }

    // Setters
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setType(string $type): void { $this->type = $type; }
    public function setUrl(string $url): void { $this->url = $url; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setIdDefi(int $id_defi): void { $this->id_defi = $id_defi; }
    public function setOrdre(int $ordre): void { $this->ordre = $ordre; }
    public function setNecessitePreuve(bool $necessite_preuve): void { $this->necessite_preuve = $necessite_preuve; }
}
?>