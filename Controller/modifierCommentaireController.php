<?php

include __DIR__ . "/crudCommentaire.php";
include __DIR__ . "/modérateur.php";
include_once __DIR__ . "/../Model/commentaires.php";

/*var_dump($contenu);
die;*/


$id=$_GET["id"];
$contenu= $_POST["contenu"];

// Modérer le commentaire lors de la modification
$etat = modererCommentaire($contenu);
if ($etat == -1) {
    $position = $_GET["position"] ?? "";
    if ($position == 'front') {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?error=inappropriate_content');
    } else {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?error=inappropriate_content');
    }
    exit;
}

/*var_dump($contenu);
die;*/
 modifierCommentaires($id,$contenu);
//header('Location:../View/FRONT OFFICE/forum.php');
$position=$_GET["position"];

if($position=='front')
{
      header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?success=comment_updated');
}
else
{
     header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php');
}
?>