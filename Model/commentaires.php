<?php

class Commentaire 
{
    private $id;
    private $date;
    private $contenu;
    private $post;
  

    public function __construct($contenu,$date,$post)
    {
      
        $this->contenu=$contenu;
        $this->date=$date;
        $this->post=$post;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function getContenu()
    {
        return $this->contenu;
    }
    public function getPost()
    {
        return $this->post;
    }
    public function setId( $id)
    {
        $this->id=$id;
    }

    public function setNom( $contenu)
    {
        $this->contenu=$contenu;
    }
    public function setDate( $date)
    {
        $this->date=$date;
    }
    public function setPost($post)
    {
        $this->post=$post;
    }
}


?>
