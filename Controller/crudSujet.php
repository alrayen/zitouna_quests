<?php
  include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/sujets.php';


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
function getPostsParJour($days = 7) {
    $conn = getDatabaseConnexion();
    
    // Correction: Utilisation de requête préparée pour éviter les injections SQL
    $sql = "SELECT DATE(date_sujets) as jour, COUNT(*) as count 
            FROM sujets 
            WHERE date_sujets >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
            GROUP BY DATE(date_sujets) 
            ORDER BY jour ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':days', $days, PDO::PARAM_INT);
    $stmt->execute();
    
    // Récupérer les résultats sous forme de tableau associatif
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Transformer en tableau associatif [date => count]
    $data = [];
    foreach ($results as $row) {
        $data[$row['jour']] = (int)$row['count'];
    }
    
    return $data;
}

// Ajoutez aussi cette fonction pour obtenir le total des posts
function getTotalPosts() {
    $conn = getDatabaseConnexion();
    $sql = "SELECT COUNT(*) as total FROM sujets";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'] ?? 0;
}

// Fonction pour obtenir les posts d'aujourd'hui
function getPostsToday() {
    $conn = getDatabaseConnexion();
    $sql = "SELECT COUNT(*) as aujourdhui 
            FROM sujets 
            WHERE DATE(date_sujets) = CURDATE()";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['aujourdhui'] ?? 0;
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
function incrementLike($id)
{
  try
    {
             $conn=getDatabaseConnexion();
             $sql="UPDATE sujets SET likes=likes+1 WHERE id='$id'";
             
             $conn->query($sql);
           
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    } 
}
function decrementLike($id)
{
   try
    {
             $conn=getDatabaseConnexion();
             $sql="UPDATE sujets SET likes=GREATEST(likes-1,0 )WHERE id='$id'";
             
             $conn->query($sql);
           
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();

    } 
}

?>