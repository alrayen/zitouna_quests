<?php

include __DIR__ . "/crudCommentaire.php";
include __DIR__ . "/modérateur.php";
include_once __DIR__ . "/../Model/commentaires.php";

$contenu = $_POST["contenu"];
$date = date("Y-m-d");
$post = $_GET["post"];
$position = $_GET["position"];

$commentaire = new Commentaire($contenu, $date, $post);

// Modérer le commentaire
$etat = modererCommentaire($contenu);


if ($etat == 1) {
    // Le commentaire est approprié, on peut l'ajouter
    createCommentaire($commentaire);
    
    if ($position == 'front') {
        header('Location:../View/FRONT OFFICE/forum.php?success=comment_added#post-' . $post);
    } else {
        header('Location:../View/BACK OFFICE/build/pages/posts.php?success=comment_added');
    }
} else {
    // Le commentaire contient du contenu inapproprié
    if ($position == 'front') {
        header('Location:../View/FRONT OFFICE/forum.php?error=inappropriate_content&post=' . $post);
    } else {
        header('Location:../View/BACK OFFICE/build/pages/posts.php?error=inappropriate_content');
    }
}
exit();
?>