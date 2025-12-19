<?php
  include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/sujets.php';


  function createSujet($sujet)
  {
    $nom= $sujet->getNom();
    $date= $sujet->getDate();
    $image=$sujet->getImage();
    $titre=$sujet->getTitre();
    $id_user=$sujet->getId_user();
    
    try
    {
             $conn=getDatabaseConnexion();
             $sql="INSERT INTO sujets (nom, date_sujets, titre, imagee, id_user, likes) VALUES (:nom, :date, :titre, :image, :id_user, 0)";
             $stmt = $conn->prepare($sql);
             return $stmt->execute([
                 'nom' => $nom,
                 'date' => $date,
                 'titre' => $titre,
                 'image' => $image,
                 'id_user' => $id_user
             ]);
    }
    catch(PDOException $e )
    {
        error_log("Error in createSujet: " . $e->getMessage());
        return false;
    }
  }

function afficherSujet()
{
    try {
        $conn=getDatabaseConnexion();
        $sql="SELECT s.*, u.nom as user_nom, u.Prenom as user_prenom, u.photo as user_photo 
              FROM sujets s 
              LEFT JOIN user u ON s.id_user = u.id_user 
              ORDER BY s.date_sujets DESC";
        $sujets=$conn->query($sql);
        return $sujets;
    } catch(PDOException $e) {
        echo $e->getMessage();
        return false;
    }
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

/**
 * Récupère le nombre de posts ajoutés aujourd'hui
 * @return int Nombre de posts aujourd'hui
 */
function getPostsToday() {
    $conn = getDatabaseConnexion();
    $sql = "SELECT COUNT(*) as aujourdhui 
            FROM sujets 
            WHERE DATE(date_sujets) = CURDATE()";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['aujourdhui'] ?? 0;
}

// Fonction pour obtenir tous les utilisateurs (utilisée pour le forum)
function getAllUsersForum() {
    try {
        $conn = getDatabaseConnexion();
        $sql = "SELECT * FROM user ORDER BY id_user DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Fonction pour obtenir le total des utilisateurs
function getTotalUsers() {
    $conn = getDatabaseConnexion();
    $sql = "SELECT COUNT(*) as total FROM user";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'] ?? 0;
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
             $sql="DELETE FROM sujets where id=:id";
             $stmt = $conn->prepare($sql);
             $stmt->execute(['id' => $id]);
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();
    } 
}
function modifierposts($id, $contenu, $titre = null)
{
    try {
        $conn = getDatabaseConnexion();
        if ($titre !== null) {
            $sql = "UPDATE sujets SET nom=:contenu, titre=:titre WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'contenu' => $contenu,
                'titre' => $titre,
                'id' => $id
            ]);
        } else {
            $sql = "UPDATE sujets SET nom=:contenu WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'contenu' => $contenu,
                'id' => $id
            ]);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
function incrementLike($id)
{
  try
    {
             $conn=getDatabaseConnexion();
             $sql="UPDATE sujets SET likes=COALESCE(likes, 0)+1 WHERE id=:id";
             $stmt = $conn->prepare($sql);
             $stmt->execute(['id' => $id]);
    }
    catch(PDOException $e )
    {
        error_log("Error in incrementLike: " . $e->getMessage());
    } 
}
function decrementLike($id)
{
   try
    {
             $conn=getDatabaseConnexion();
             $sql="UPDATE sujets SET likes=GREATEST(COALESCE(likes, 0)-1, 0) WHERE id=:id";
             $stmt = $conn->prepare($sql);
             $stmt->execute(['id' => $id]);
    }
    catch(PDOException $e )
    {
        error_log("Error in decrementLike: " . $e->getMessage());
    } 
}

?>