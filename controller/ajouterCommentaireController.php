<?php

include __DIR__ . "/crudCommentaire.php";
include_once __DIR__ . "/../model/commentaires.php";
$contenu= $_POST["contenu"];
/*var_dump($contenu);
die;*/
$date=date("Y-m-d");
$post=$_GET["post"];
$commentaire= new Commentaire($contenu,$date,$post);


/*var_dump($post);
die;*/
createCommentaire($commentaire);
header('Location:../view/frontoffice/forum.php');
/*$position=$_GET["position"];
if($position=='front')
{
     header('Location:../view/frontoffice/forum.php');
}
else
{
     header('Location:../view/backoffice/build/pages/posts.php');
}*/
?>