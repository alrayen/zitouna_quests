<?php

class Sujet 
{
    private $id;
    private $nom;
    private $date;
    private $titre;
    private $image;
    private $like;
    private $id_user;
   


    public function __construct($nom,$date,$titre,$image,$id_user = null)
    {
      
        $this->nom=$nom;
        $this->date=$date;
        $this->image=$image;
        $this->titre=$titre;
        $this->id_user=$id_user;
        $this->like=0;
       
       
    }
    public function getId_user()
    {
        return $this->id_user;
    }
    public function setId_user($id_user)
    {
        $this->id_user=$id_user;
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
     public function getTitre()
    {
        return $this->titre;
    }
     public function getImage()
    {
        return $this->image;
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
     public function setTitre( $titre)
    {
        $this->titre=$titre;
    }
    public function setImage( $image)
    {
        $this->image=$image;
    }
    
}

?>
