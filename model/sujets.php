<?php

class Sujet 
{
    private $id;
    private $nom;
    private $date;

    public function __construct($nom,$date)
    {
      
        $this->nom=$nom;
        $this->date=$date;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function setId( $id)
    {
        $this->id=$id;
    }

    public function setNom( $nom)
    {
        $this->nom=$nom;
    }
    public function setDate( $date)
    {
        $this->date=$date;
    }
}


?>
