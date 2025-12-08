<?php

include __DIR__ . "/crudCommentaire.php";
include_once __DIR__ . "/../model/commentaires.php";

/*var_dump($contenu);
die;*/


$id=$_GET["id"];
$contenu= $_POST["contenu"];
/*var_dump($contenu);
die;*/
 modifierCommentaires($id,$contenu);
//header('Location:../view/frontoffice/forum.php');
$position=$_GET["position"];

if($position=='front')
{
      header('Location:../view/frontoffice/forum.php?success=comment_updated#comment-' . $id);
}
else
{
     header('Location:../view/backoffice/build/pages/posts.php');
}
?>