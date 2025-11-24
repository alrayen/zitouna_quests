<?php
  include __DIR__ . '/../config.php';
include_once __DIR__ . '/../model/sujets.php';


  function createSujet($sujet)
  {
    $nom= $sujet->getNom();
    $date= $sujet->getDate();
    
    try
    {
             $conn=getDatabaseConnexion();
             $sql="INSERT INTO sujets ( nom ,date_sujets) VALUES('$nom','$date')";
             
             $conn->exec($sql);
            
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    }
  }

function afficherSujet()
{
    
          $conn=getDatabaseConnexion();
          $sql="SELECT * FROM sujets ";
          $sujets=$conn->query($sql);

    return $sujets;
}

function deletepost($id)
{
     try
    {
             $conn=getDatabaseConnexion();
             $sql="DELETE FROM sujets where id='$id'";
             
             $conn->query($sql);
             
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    } 
}
function modifierposts($id,$contenu)
{
   try
    {
             $conn=getDatabaseConnexion();
             $sql="UPDATE sujets SET nom='$contenu' WHERE id='$id'";
             
             $conn->query($sql);
           
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    } 
}

?>