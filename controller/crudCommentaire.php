<?php
  include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../model/commentaires.php';


  function createCommentaire($commentaire)
{
    $contenu  = $commentaire->getContenu();
    $date     = $commentaire->getDate();
    $post     = $commentaire->getPost();

    try {
        $conn = getDatabaseConnexion();
        
        $sql = "INSERT INTO commentaires (contenu, date_commentaires, poste)
                VALUES (:contenu, :date_commentaires, :poste)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':date_commentaires', $date);
        $stmt->bindParam(':poste', $post, PDO::PARAM_INT);

        $stmt->execute();
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

  function afficherCommentaire()
{
    
          $conn=getDatabaseConnexion();
          $sql="SELECT * FROM commentaires ";
          $commentaires=$conn->query($sql);

    return $commentaires;
}
function afficherCommentaireParPost($id)
{
    
          $conn=getDatabaseConnexion();
          $sql="SELECT * FROM commentaires where poste='$id'";
          $commentaires=$conn->query($sql);

    return $commentaires;
}

function deleteCommentaires($id)
{
     try
    {
             $conn=getDatabaseConnexion();
             $sql="DELETE FROM commentaires where id='$id'";
             
             $conn->query($sql);
             
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    } 
}
function modifierCommentaires($id,$contenu)
{
   try
    {
             $conn=getDatabaseConnexion();
             $sql="UPDATE commentaires SET contenu='$contenu' WHERE id='$id'";
             
             $conn->query($sql);
           
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    } 
}
?>