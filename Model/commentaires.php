<?php

class Commentaire 
{
    private $id;
    private $date;
    private $contenu;
    private $post;
    private $id_user;
  

    public function __construct($contenu,$date,$post, $id_user = null)
    {
      
        $this->contenu=$contenu;
        $this->date=$date;
        $this->post=$post;
        $this->id_user=$id_user;
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
