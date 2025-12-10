<?php

class Sponsor {
    private ?int $id;
    private string $nom;
    private string $secteur;
    private string $contact;
    private float $contribution;

    public function __construct(string $nom, string $secteur, string $contact, float $contribution, ?int $id = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->secteur = $secteur;
        $this->contact = $contact;
        $this->contribution = $contribution;
    }

    // Getters with return types
    public function getId(): ?int { 
        return $this->id; 
    }

    public function getNom(): string { 
        return $this->nom; 
    }

    public function getSecteur(): string { 
        return $this->secteur; 
    }

    public function getContact(): string { 
        return $this->contact; 
    }

    public function getContribution(): float { 
        return $this->contribution; 
    }


    public function setNom(string $nom): void { 
        $this->nom = $nom; 
    }

    public function setSecteur(string $secteur): void { 
        $this->secteur = $secteur; 
    }

    public function setContact(string $contact): void { 
        $this->contact = $contact; 
    }

    public function setContribution(float $contribution): void { 
        $this->contribution = $contribution; 
    }
}
?>