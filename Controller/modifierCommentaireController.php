<?php

include __DIR__ . "/crudCommentaire.php";
include_once __DIR__ . "/../Model/commentaires.php";

/*var_dump($contenu);
die;*/


$id=$_GET["id"];
$contenu= $_POST["contenu"];
/*var_dump($contenu);
die;*/
 modifierCommentaires($id,$contenu);
//header('Location:../View/FRONT OFFICE/forum.php');
$position=$_GET["position"];

if($position=='front')
{
      header('Location:../View/FRONT OFFICE/forum.php?success=comment_updated#comment-' . $id);
}
else
{
     header('Location:../View/BACK OFFICE/build/pages/posts.php');
}
?>