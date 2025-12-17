<?php

   include "crudSujet.php";

header('Content-Type: application/json');
$id=$_GET['id'];
$choix=$_GET['choix'];

if($choix=='increment')
{
    incrementLike($id);
}
else{
    decrementLike($id);
}
header('Location: ../View/FRONT OFFICE/forum.php');

/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sujet_id']) && isset($_POST['action'])) {
    $sujetId = intval($_POST['sujet_id']);
    $action = $_POST['action'];
    
    if ($action === 'like') {
        $newLikes = incrementLike($sujetId);
    } else if ($action === 'unlike') {
        $newLikes = decrementLike($sujetId);
    }
    
    if ($newLikes !== false) {
        echo json_encode(['success' => true, 'likes' => $newLikes]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
}*/

?>