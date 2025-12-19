<?php
  include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../Model/commentaires.php';


  function createCommentaire($commentaire)
{
    $contenu  = $commentaire->getContenu();
    $date     = $commentaire->getDate();
    $post     = $commentaire->getPost();
    $id_user  = $commentaire->getId_user();

    try {
        $conn = getDatabaseConnexion();
        
        $sql = "INSERT INTO commentaires (contenu, date_commentaires, poste, id_user, etat)
                VALUES (:contenu, :date_commentaires, :poste, :id_user, 0)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':date_commentaires', $date);
        $stmt->bindParam(':poste', $post, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        $stmt->execute();
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

/**
 * Récupère tous les commentaires
 * @return array Liste de tous les commentaires
 */
function afficherCommentaire()
{
    try {
        $conn = getDatabaseConnexion();
        $sql = "SELECT * FROM commentaires ORDER BY date_commentaires DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
        return [];
    }
}

function afficherCommentaireParPost($id)
{
    try {
        $conn=getDatabaseConnexion();
        $sql="SELECT c.*, u.nom as user_nom, u.Prenom as user_prenom, u.photo as user_photo 
              FROM commentaires c 
              LEFT JOIN user u ON c.id_user = u.id_user 
              WHERE c.poste=:id 
              ORDER BY c.date_commentaires ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt;
    } catch(PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function deleteCommentaires($id)
{
     try
    {
             $conn=getDatabaseConnexion();
             $sql="DELETE FROM commentaires where id=:id";
             $stmt = $conn->prepare($sql);
             $stmt->execute(['id' => $id]);
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
             $sql="UPDATE commentaires SET contenu=:contenu WHERE id=:id";
             $stmt = $conn->prepare($sql);
             $stmt->execute([
                 'contenu' => $contenu,
                 'id' => $id
             ]);
    }
    catch(PDOException $e )
    {
        echo $e->getMessage();
    } 
}














function getCommentairesParJour($days = 7) {
    $conn = getDatabaseConnexion();
    
    $sql = "SELECT DATE(date_commentaires) as jour, COUNT(*) as count 
            FROM commentaires 
            WHERE date_commentaires >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
            GROUP BY DATE(date_commentaires) 
            ORDER BY jour ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':days', $days, PDO::PARAM_INT);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = [];
    foreach ($results as $row) {
        $data[$row['jour']] = (int)$row['count'];
    }
    
    return $data;
}

/**
 * Récupère le nombre total de commentaires
 * @return int Nombre total de commentaires
 */
function getTotalCommentaires() {
    $conn = getDatabaseConnexion();
    $sql = "SELECT COUNT(*) as total FROM commentaires";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'] ?? 0;
}

/**
 * Récupère le nombre de commentaires ajoutés aujourd'hui
 * @return int Nombre de commentaires aujourd'hui
 */
function getCommentairesToday() {
    $conn = getDatabaseConnexion();
    $sql = "SELECT COUNT(*) as aujourdhui 
            FROM commentaires 
            WHERE DATE(date_commentaires) = CURDATE()";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['aujourdhui'] ?? 0;
}

/**
 * Récupère les statistiques globales des commentaires
 * @return array Tableau avec différentes statistiques
 */
function getCommentairesStats() {
    $conn = getDatabaseConnexion();
    
    $sql = "SELECT 
                COUNT(*) as total,
                COUNT(DISTINCT DATE(date_commentaires)) as jours_actifs,
                AVG(par_jour) as moyenne_quotidienne,
                MAX(par_jour) as max_quotidien
            FROM (
                SELECT DATE(date_commentaires), COUNT(*) as par_jour
                FROM commentaires
                GROUP BY DATE(date_commentaires)
            ) as stats";
    
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}

/**
 * Récupère les commentaires par post
 * @return array Tableau avec le nombre de commentaires par post
 */
function getCommentairesParPosts($limit = 10) {
    $conn = getDatabaseConnexion();
    
    $sql = "SELECT 
                poste,
                COUNT(*) as nombre_commentaires,
                s.nom as nom_post
            FROM commentaires c
            LEFT JOIN sujets s ON c.poste = s.id
            GROUP BY poste
            ORDER BY nombre_commentaires DESC
            LIMIT :limit";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les derniers commentaires
 * @param int $limit Nombre de commentaires à récupérer
 * @return array Derniers commentaires
 */
function getDerniersCommentaires($limit = 5) {
    $conn = getDatabaseConnexion();
    
    $sql = "SELECT 
                c.id,
                c.contenu,
                c.date_commentaires,
                s.nom as nom_post
            FROM commentaires c
            LEFT JOIN sujets s ON c.poste = s.id
            ORDER BY c.date_commentaires DESC
            LIMIT :limit";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les heures les plus actives pour les commentaires
 * @return array Nombre de commentaires par heure
 */
function getCommentairesParHeure() {
    $conn = getDatabaseConnexion();
    
    $sql = "SELECT 
                HOUR(date_commentaires) as heure,
                COUNT(*) as nombre
            FROM commentaires
            GROUP BY HOUR(date_commentaires)
            ORDER BY heure";
    
    $stmt = $conn->query($sql);
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = [];
    for ($i = 0; $i < 24; $i++) {
        $data[$i] = 0;
    }
    
    foreach ($results as $row) {
        $data[$row['heure']] = (int)$row['nombre'];
    }
    
    return $data;
}
?>