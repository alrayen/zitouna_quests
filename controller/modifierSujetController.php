<?php
include __DIR__ . "/crudSujet.php";

$id=$_GET['id'];
$contenu=$_POST['nom'];
modifierPosts($id,$contenu);
$position=$_GET["position"];
if($position=='front')
{
     header('Location:../view/frontoffice/forum.php?success=post_updated#post-' . $id);
}
else
{
     header('Location:../view/backoffice/build/pages/posts.php');
}




?>