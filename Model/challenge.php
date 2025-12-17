<?php
class Challenge {
    private int $id_defi;
    private string $titre;
    private string $description;
    private string $categorie;   
    private int $points;
    private int $time;
    private string $difficulty;  
    private string $status;
    private string $place;

    public function __construct(
        int $id_defi, 
        string $titre, 
        string $description, 
        string $categorie,       
        int $points, 
        int $time, 
        string $difficulty,      
        string $status, 
        string $place
    ) {
        $this->id_defi = $id_defi;
        $this->titre = $titre;
        $this->description = $description;
        $this->categorie = $categorie;
        $this->points = $points;
        $this->time = $time;
        $this->difficulty = $difficulty;
        $this->status = $status;
        $this->place = $place;
    }


    public function getIdDefi(): int {
        return $this->id_defi;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getCategorie(): string { 
        return $this->categorie;
    }

    public function getPoints(): int {
        return $this->points;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function getDifficulty(): string { 
        return $this->difficulty;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getPlace(): string {
        return $this->place;
    }


    public function setIdDefi(int $id_defi): void {
        $this->id_defi = $id_defi;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setCategorie(string $categorie): void { // Updated parameter type
        $this->categorie = $categorie;
    }

    public function setPoints(int $points): void {
        $this->points = $points;
    }

    public function setTime(int $time): void {
        $this->time = $time;
    }

    public function setDifficulty(string $difficulty): void { // Updated parameter type
        $this->difficulty = $difficulty;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setPlace(string $place): void {
        $this->place = $place;
    }
}
?>