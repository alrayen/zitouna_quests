<?php
header('Content-Type: application/json');

// Démarrer la capture de sortie pour éviter les erreurs d'affichage
ob_start();

try {
    include_once "../../controller/crudSujet.php";
    include_once "../../controller/crudCommentaire.php";
    
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    
    if ($post_id <= 0) {
        ob_end_clean();
        echo json_encode([
            'success' => false,
            'message' => 'ID de post invalide'
        ]);
        exit;
    }
    
    // Récupérer le sujet
    $sujet = afficherSujetParId($post_id);
    
    // Récupérer les commentaires
    $result = afficherCommentaireParPost($post_id);
    
    // Convertir le résultat en tableau
    $commentaires = [];
    if ($result) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = $row;
        }
    }
    
    // Nettoyer le buffer de sortie
    ob_end_clean();
    
    // Retourner la réponse JSON
    echo json_encode([
        'success' => true,
        'sujet' => $sujet,
        'comments' => $commentaires,
        'count' => count($commentaires)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>