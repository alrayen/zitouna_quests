<?php

class Quiz {

    private ?int $id_quiz;
    private string $titre;
    private string $categorie;
    private string $niveau;
    private int $points;

    public function __construct(?int $id_quiz, string $titre, string $categorie, string $niveau, int $points) {
        $this->id_quiz = $id_quiz;
        $this->titre = $titre;
        $this->categorie = $categorie;
        $this->niveau = $niveau;
        $this->points = $points;
    }

    // Getters...
    public function getIdQuiz(): ?int { return $this->id_quiz; }
    public function getTitre(): string { return $this->titre; }
    public function getCategorie(): string { return $this->categorie; }
    public function getNiveau(): string { return $this->niveau; }
    public function getPoints(): int { return $this->points; }

    // Setters (with the bug fixed)
    public function setTitre(string $titre): void { $this->titre = $titre; }
    public function setCategorie(string $categorie): void { $this->categorie = $categorie; }
    public function setNiveau(string $niveau): void { $this->niveau = $niveau; }
    public function setPoints(int $points): void { $this->points = $points; }
}
?>