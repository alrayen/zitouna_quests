<?php
  include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../model/sujets.php';


  function createSujet($sujet)
  {
    $nom= $sujet->getNom();
    $date= $sujet->getDate();
    $image=$sujet->getImage();
    
    try
    {
             $conn=getDatabaseConnexion();
             $sql="INSERT INTO sujets ( nom ,date_sujets,titre,imagee) VALUES('$nom','$date','$titre','$image')";
             
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
function afficherSujetParId($id)
{
    $conn = getDatabaseConnexion();
    $sql = "SELECT * FROM sujets WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
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