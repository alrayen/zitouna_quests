<?php
session_start();
include __DIR__ . "/crudCommentaire.php";
include __DIR__ . "/modérateur.php";
include_once __DIR__ . "/../Model/commentaires.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php');
    exit;
}

$contenu = $_POST["contenu"] ?? "";
$date = date("Y-m-d H:i:s");
$post = $_GET["post"] ?? null;
$position = $_GET["position"] ?? "";
$id_user = $_SESSION['user_id'];

$commentaire = new Commentaire($contenu, $date, $post, $id_user);

// Modérer le commentaire
$etat = modererCommentaire($contenu);

if ($etat == 1) {
    // Le commentaire est approprié, on peut l'ajouter
    createCommentaire($commentaire);
    
    if ($position == 'front') {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?success=comment_added&post=' . $post);
    } else {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?success=comment_added');
    }
} else {
    // Le commentaire contient du contenu inapproprié
    if ($position == 'front') {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?error=inappropriate_content&post=' . $post);
    } else {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?error=inappropriate_content');
    }
}
exit();
?>